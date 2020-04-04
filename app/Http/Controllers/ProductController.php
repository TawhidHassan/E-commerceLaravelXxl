<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\ProductsAttribute;
use App\ProductsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
	public function viewProducts(){
		$products = Product::orderby('id','DESC')->get();
		// $products = json_decode(json_encode($products));
		foreach($products as $key => $val){
			$category_name = Category::where(['id'=>$val->category_id])->first();
			$products[$key]->category_name = $category_name->name;
		}
		//echo "<pre>"; print_r($products); die;
		return view('admin.products.view_product')->with(compact('products'));
	}

	public function addProduct(Request $request){



    	if($request->isMethod('post')){
    		$data = $request->all();
    		// echo "<pre>"; print_r($data); die;
    		if(empty($data['category_id'])){
    			return redirect()->back()->with('flash_message_error','Under Category is missing!');	
    		}
    		$product = new Product;
    		$product->category_id = $data['category_id'];
    		$product->product_name = $data['product_name'];
    		$product->product_code = $data['product_code'];
    		$product->product_color = $data['product_color'];
    		
    		if(!empty($data['description'])){
    			$product->description = $data['description'];
    		}else{
				$product->description = '';    			
    		}
    		if(!empty($data['care'])){
    			$product->care = $data['care'];
    		}else{
				$product->care = '';    			
    		}
    		$product->price = $data['price'];

    		// Upload Image
    		if($request->hasFile('image')){
                
                $image_tmp = Input::file('image');
    			if($image_tmp->isValid()){
    				$extension = $image_tmp->getClientOriginalExtension();
    				$filename = rand(111,99999).'.'.$extension;
    				$large_image_path = 'images/backend_images/products/large/'.$filename;
    				$medium_image_path = 'images/backend_images/products/medium/'.$filename;
    				$small_image_path = 'images/backend_images/products/small/'.$filename;
    				// Resize Images
                    Image::make($image_tmp)->save($large_image_path);
    				Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
    				Image::make($image_tmp)->resize(300,300)->save($small_image_path);

    				// Store image name in products table
    				$product->image = $filename;
    			}
    		}

    		$product->save();
    		/*return redirect()->back()->with('flash_message_success','Product has been added successfully!');*/
            return redirect('/admin/view-products')->with('flash_message_success','Product has been added successfully!');
    	}


		//category dropdown start
        $categories=Category::where(['parent_id'=>0])->get();
        $categories_dropdown="<option selected disabled>Select</option>";
        foreach($categories as $cat){
            $categories_dropdown.= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
    		foreach ($sub_categories as $sub_cat) {
    			$categories_dropdown .= "<option value = '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
    		}
		}
		//category dropdown end
        return view('admin.products.add_product')->with(compact('categories_dropdown'));
	}
	
	public function editProduct(Request $request,$id=null)
	{

		if($request->isMethod('post'))
		{
			$data=$request->all();


			// Upload Image
    		if($request->hasFile('image')){
                
                $image_tmp = Input::file('image');
    			if($image_tmp->isValid()){
    				$extension = $image_tmp->getClientOriginalExtension();
    				$filename = rand(111,99999).'.'.$extension;
    				$large_image_path = 'images/backend_images/products/large/'.$filename;
    				$medium_image_path = 'images/backend_images/products/medium/'.$filename;
    				$small_image_path = 'images/backend_images/products/small/'.$filename;
    				// Resize Images
                    Image::make($image_tmp)->save($large_image_path);
    				Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
    				Image::make($image_tmp)->resize(300,300)->save($small_image_path);
    			}
    		}else{
				$filename=$data['current_image'];
			}


			if(empty($data['description']))
			{
				$data['description']="";
			}

			if(empty($data['care']))
			{
				$data['care']="";
			}
			Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],
			'product_name'=>$data['product_name'],
			'product_code'=>$data['product_code'],
			'product_color'=>$data['product_color'],
			'description'=>$data['description'],
			'care'=>$data['care'],
			'price'=>$data['price'],
			'image'=>$filename,
			]);
			return redirect()->back()->with('flash_message_success','product updated Successfully!');

		}


		// get that product
			$productDetails=Product::where(['id'=>$id])->first();
			//category dropdown start
			$categories=Category::where(['parent_id'=>0])->get();
			$categories_drop_down="<option selected disabled>Select</option>";
			foreach($categories as $cat){
				if($cat->id==$productDetails->category_id)
				{
					$selected="selected";
				}else{
					$selected="";
				}
				$categories_drop_down.= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
				$sub_categories = Category::where(['parent_id'=>$cat->id])->get();
				foreach ($sub_categories as $sub_cat) {
					if($sub_cat->id==$productDetails->category_id)
				{
					$selected="selected";
				}else{
					$selected="";
				} 
					$categories_drop_down .= "<option value = '".$sub_cat->id."' ".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
				}
			}
			//category dropdown end
			return view('admin.products.edit_product')->with(compact('productDetails','categories_drop_down'));
	}

	 public function deleteProductImage(Request $request,$id)
	{
		// Get Product Image
		$productImage = Product::where('id',$id)->first();

		// Get Product Image Paths
		$large_image_path = 'images/backend_images/product/large/';
		$medium_image_path = 'images/backend_images/product/medium/';
		$small_image_path = 'images/backend_images/product/small/';

		// Delete Large Image if not exists in Folder
        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }

        // Delete Medium Image if not exists in Folder
        if(file_exists($medium_image_path.$productImage->image)){
            unlink($medium_image_path.$productImage->image);
        }

        // Delete Small Image if not exists in Folder
        if(file_exists($small_image_path.$productImage->image)){
            unlink($small_image_path.$productImage->image);
        }
 
        // Delete Image from Products table
        Product::where(['id'=>$id])->update(['image'=>'']);

        return redirect()->back()->with('flash_message_success', 'Product image has been deleted successfully');
	}


	public function deleteProduct($id = null){
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Product has been deleted successfully');
	}
	
	public function addAttributes(Request $request, $id=null){
		$productDetails = Product::with('attributes')->where(['id' => $id])->first();
		$productDetails=json_decode(json_encode($productDetails));
	
		$categoryDetails = Category::where(['id'=>$productDetails->category_id])->first();
		$category_name = $categoryDetails->name;

		if($request->isMethod('post'))
		{
			$data = $request->all();

		    foreach($data['sku'] as $key => $val){
				if(!empty($val))
				{

					//prevent duplicate SKU check
					$attrCountSKU=ProductsAttribute::where('sku',$val)->count();
					if($attrCountSKU>0)
					{
						return redirect('admin/add-attributes/'.$id)->with('flash_message_error', 'SKU already exists! please add another SKU.');
					}
					//prevent duplicate SKU check

					$attrCountSizes = ProductsAttribute::where(['product_id'=>$id,'size'=>$data['size'][$key]])->count();
                    if($attrCountSizes>0){
                        return redirect('admin/add-attributes/'.$id)->with('flash_message_error', 'Attribute already exists. Please add another Attribute.');    
                    }

				$attr = new ProductsAttribute();
				$attr->product_id = $id;
				$attr->sku = $val;
				$attr->size = $data['size'][$key];
				$attr->price = $data['price'][$key];
				$attr->stock = $data['stock'][$key];
				$attr->save();
				}
			}
			return redirect('admin/add-attributes/'.$id)->with('flash_message_success', 'Product Attributes has been added successfully');
		}



		return view('admin.products.add_attributes')->with(compact('productDetails','category_name'));	

	}

	public function editAttributes(Request $request, $id=null)
	{
		if($request->isMethod('post')){
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
            foreach($data['idAttr'] as $key=> $attr){
                if(!empty($attr)){
                    ProductsAttribute::where(['id' => $data['idAttr'][$key]])->update(['price' => $data['price'][$key], 'stock' => $data['stock'][$key]]);
                }
            }
            return redirect('admin/add-attributes/'.$id)->with('flash_message_success', 'Product Attributes has been updated successfully');
        }
	}

	public function deleteAttributes($id=null)
	{
		ProductsAttribute::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Product Attribute has been deleted successfully');
	}

	public function products($url=null)
	{

		//show 404 page if category url dose not exist
		$countCategory=Category::where(['url'=>$url,'status'=>1])->count();
		if($countCategory==0)
		{
			abort(404);
		}

		 //get category and sub cetgory
		 $categories=Category::with('categories')->where(['parent_id'=>0])->get();
			$categoryDetails=Category::where(['url'=>$url])->first();

			if($categoryDetails->parent_id==0)
			{
			//if url is sub category url	
			$subCategories = Category::where(['parent_id'=>$categoryDetails->id])->get();
    		$subCategories = json_decode(json_encode($subCategories));
    		foreach($subCategories as $subcat){
    			$cat_ids[] = $subcat->id;
    		}
			$productsAll = Product::whereIn('products.category_id', $cat_ids)->get();
		
			}else{
				//if url is sub category url
				$productsAll=Product::where(['category_id'=>$categoryDetails->id])->get();
			}

			return view('products.listing')->with(compact('categories','categoryDetails','productsAll'));
	}


	//product details page
	public function product($id=null)
	{
		//get product data by id
		$productDetails=Product::with('attributes')->where(['id'=>$id])->first();
		
		 //get category and sub cetgory
		 $categories=Category::with('categories')->where(['parent_id'=>0])->get();

		 $productAltImages= ProductsImage::where(['product_id' => $id])->get();
		//  $productAltImages=json_decode(json_encode($productAltImages));
		// 	echo "<pre>"; print_r($productAltImages);die;

		//product stock calculation
		$totla_stock=ProductsAttribute::where('product_id',$id)->sum('stock');

		//related  same category product
		$relatedProducts=Product::where('id',"!=",$id)->where(['category_id'=>$productDetails->category_id])->get();
	    // $relatedProducts=json_decode(json_encode($relatedProducts));
		// echo "<pre>"; print_r($relatedProducts);die;
		/*foreach($relatedProducts->chunk(3) as $chunk){
            foreach($chunk as $item){
                echo $item; echo "<br>"; 
            }   
            echo "<br><br><br>";
        }*/
        
		return view('products.detail')->with(compact('productDetails','categories','productAltImages','totla_stock','relatedProducts'));

	}

	//get prodiuct price by size
	public function productPrice(Request $request)
	{
		$data=$request->all();
		// echo "<pre>"; print_r($data);
		$proArr=explode("-",$data['idSize']);
		$proAttr=ProductsAttribute::where(['product_id'=>$proArr[0],'size'=>$proArr[1]])->first();
		$price=$proAttr->price;
		$stock=$proAttr->stock;
		echo $price;
		echo "#";
		echo $stock;
	}

	public function addImages(Request $request, $id=null){
        $productDetails = Product::where(['id' => $id])->first();

        $categoryDetails = Category::where(['id'=>$productDetails->category_id])->first();
        $category_name = $categoryDetails->name;

        if($request->isMethod('post')){
            $data = $request->all();
            if ($request->hasFile('image')) {
                $files = $request->file('image');
                foreach($files as $file){
                   // Upload Images after Resize
				   $image = new ProductsImage;
				   $extension = $file->getClientOriginalExtension();
				   $fileName = rand(111,99999).'.'.$extension;
				   $large_image_path = 'images/backend_images/products/large/'.$fileName;
    				$medium_image_path = 'images/backend_images/products/medium/'.$fileName;
    				$small_image_path = 'images/backend_images/products/small/'.$fileName; 
				   Image::make($file)->save($large_image_path);
				   Image::make($file)->resize(600, 600)->save($medium_image_path);
				   Image::make($file)->resize(300, 300)->save($small_image_path);
				   $image->image = $fileName;  
				   $image->product_id = $data['product_id'];
				   $image->save();
                }   
            }

            return redirect('admin/add-images/'.$id)->with('flash_message_success', 'Product Images has been added successfully');

		}
		$productImages = ProductsImage::where(['product_id' => $id])->orderBy('id','DESC')->get();

        $title = "Add Images";
        return view('admin.products.add_iamges')->with(compact('title','productDetails','category_name','productImages'));
    }
	
	public function deleteProductAltImage($id=null){

        // Get Product Image
        $productImage = ProductsImage::where('id',$id)->first();

        // Get Product Image Paths
        $large_image_path = 'images/backend_images/product/large/';
        $medium_image_path = 'images/backend_images/product/medium/';
        $small_image_path = 'images/backend_images/product/small/';

        // Delete Large Image if not exists in Folder
        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }

        // Delete Medium Image if not exists in Folder
        if(file_exists($medium_image_path.$productImage->image)){
            unlink($medium_image_path.$productImage->image);
        }

        // Delete Small Image if not exists in Folder
        if(file_exists($small_image_path.$productImage->image)){
            unlink($small_image_path.$productImage->image);
        }

        // Delete Image from Products Images table
        ProductsImage::where(['id'=>$id])->delete();

        return redirect()->back()->with('flash_message_success', 'Product alternate mage has been deleted successfully');
    }
}

