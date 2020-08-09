<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Coupon;
use App\Country;
use App\Product;
use App\Category;
use App\OrdersProduct;
use App\ProductsImage;
use App\DeliveryAddress;
use App\ProductsAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
	public function viewProducts(){
		$products = Product::orderby('id','DESC')->get();
        // $products = json_decode(json_encode($products));
        foreach($products as $key => $val){
			$category_name = Category::where(['id'=>$val->category_id])->first();
			// echo $val;die;
            $products[$key]->category_name = $category_name->name;
		}
		
		$products = json_decode(json_encode($products));
        // echo "<pre>"; print_r($products); die;
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
			if(empty($data['status'])){
                $status='0';
            }else{
                $status='1';
			}
			if(empty($data['feature_iten'])){
                $feature_iten='0';
            }else{
                $feature_iten='1';
			}
			if(!empty($data['sleeve'])){
                $product->sleeve = $data['sleeve'];
            }else{
                $product->sleeve = ''; 
			}
			if(!empty($data['pattern'])){
                $product->pattern = $data['pattern'];
            }else{
                $product->pattern = ''; 
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
			
			// Upload Video
			if($request->hasFile('video')){
				$video_tmp = Input::file('video');
				$video_name = $video_tmp->getClientOriginalName();
				$video_path = 'videos/';
				$video_tmp->move($video_path,$video_name);
				$product->video = $video_name;
			}


			$product->feature_iten = $feature_iten;
			$product->status = $status;
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

		$sleeveArray = array('Full Sleeve','Half Sleeve','Short Sleeve','Sleeveless');
		$patternArray = array('Checked','Plain','Printed','Self','Solid');
		//category dropdown end
        return view('admin.products.add_product')->with(compact('categories_dropdown','sleeveArray','patternArray'));
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

			// Upload Video
            if($request->hasFile('video')){
                $video_tmp = Input::file('video');
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path,$video_name);
                $videoName = $video_name;
            }else if(!empty($data['current_video'])){
                $videoName = $data['current_video'];
            }else{
                $videoName = '';
            }


			if(empty($data['description']))
			{
				$data['description']="";
			}

			if(empty($data['care']))
			{
				$data['care']="";
			}
			if(!empty($data['sleeve'])){
                $sleeve = $data['sleeve'];
            }else{
                $sleeve = ''; 
            }
			if(empty($data['feature_iten'])){
                $feature_iten='0';
            }else{
                $feature_iten='1';
            }
			if(empty($data['status'])){
                $status='0';
            }else{
                $status='1';
            }
			if(!empty($data['pattern'])){
                $pattern = $data['pattern'];
            }else{
                $pattern = ''; 
            }
			Product::where(['id'=>$id])->update(['feature_iten'=>$feature_iten,'category_id'=>$data['category_id'],
			'product_name'=>$data['product_name'],
			'product_code'=>$data['product_code'],
			'product_color'=>$data['product_color'],
			'description'=>$data['description'],
			'care'=>$data['care'],
			'sleeve'=>$data['sleeve'],
			'price'=>$data['price'],
			'image'=>$filename,
			'video'=>$videoName,
			'status'=>$status,
			'pattern'=>$pattern,
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


			$sleeveArray = array('Full Sleeve','Half Sleeve','Short Sleeve','Sleeveless');

			$patternArray = array('Checked','Plain','Printed','Self','Solid');


			return view('admin.products.edit_product')->with(compact('productDetails','categories_drop_down','sleeveArray','patternArray'));
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

	public function deleteProductVideo($id){
        // Get Video Name 
        $productVideo = Product::select('video')->where('id',$id)->first();

        // Get Video Path
        $video_path = 'videos/';

        // Delete Video if exists in videos folder
        if(file_exists($video_path.$productVideo->video)){
            unlink($video_path.$productVideo->video);
        }

        // Delete Video from Products table
        Product::where('id',$id)->update(['video'=>'']);

        return redirect()->back()->with('flash_message_success','Product Video has been deleted successfully');
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
			// Show 404 Page if Category does not exists
			$categoryCount = Category::where(['url'=>$url,'status'=>1])->count();
			if($categoryCount==0){
				abort(404);
			}

			$categories = Category::with('categories')->where(['parent_id' => 0])->get();

			$categoryDetails = Category::where(['url'=>$url])->first();
			if($categoryDetails->parent_id==0){
				$subCategories = Category::where(['parent_id'=>$categoryDetails->id])->get();
				$subCategories = json_decode(json_encode($subCategories));
				foreach($subCategories as $subcat){
					$cat_ids[] = $subcat->id;
				}
				$productsAll = Product::whereIn('products.category_id', $cat_ids)->where('products.status','1')->orderBy('products.id','Desc');
			}else{
				$productsAll = Product::where(['products.category_id'=>$categoryDetails->id])->where('products.status','1')->orderBy('products.id','Desc');
				$mainCategory = Category::where('id',$categoryDetails->parent_id)->first();
			}

			if(!empty($_GET['color'])){
				$colorArray = explode('-',$_GET['color']);
				$productsAll = $productsAll->whereIn('products.product_color',$colorArray);
			}

			if(!empty($_GET['sleeve'])){
				$sleeveArray = explode('-',$_GET['sleeve']);
				$productsAll = $productsAll->whereIn('products.sleeve',$sleeveArray);
			}
			if(!empty($_GET['pattern'])){
				$patternArray = explode('-',$_GET['pattern']);
				$productsAll = $productsAll->whereIn('products.pattern',$patternArray);
			}

			if(!empty($_GET['size'])){
				$sizeArray = explode('-',$_GET['size']);
				$productsAll = $productsAll->join('products_attributes','products_attributes.product_id','=','products.id')
				->select('products.*','products_attributes.product_id','products_attributes.size')
				->groupBy('products_attributes.product_id')
				->whereIn('products_attributes.size',$sizeArray);
			}
	
	

				$productsAll = $productsAll->paginate(6);
				/*$productsAll = json_decode(json_encode($productsAll));
				echo "<pre>"; print_r($productsAll); die;*/


				///get all color and show in side bar
				/*$colorArray = array('Black','Blue','Brown','Gold','Green','Orange','Pink','Purple','Red','Silver','White','Yellow');*/
				$colorArray = Product::select('product_color')->groupBy('product_color')->get();
                $colorArray = array_flatten(json_decode(json_encode($colorArray),true));

				$sleeveArray = Product::select('sleeve')->where('sleeve','!=','')->groupBy('sleeve')->get();
				$sleeveArray = array_flatten(json_decode(json_encode($sleeveArray),true));

				$patternArray = Product::select('pattern')->where('pattern','!=','')->groupBy('pattern')->get();
				$patternArray = array_flatten(json_decode(json_encode($patternArray),true));

				$sizesArray = ProductsAttribute::select('size')->groupBy('size')->get();
				$sizesArray = array_flatten(json_decode(json_encode($sizesArray),true));
				/*echo "<pre>"; print_r($sizesArray); die;*/

				$meta_title = $categoryDetails->meta_title;
				$meta_description = $categoryDetails->meta_description;
				$meta_keywords = $categoryDetails->meta_keywords;
				return view('products.listing')->with(compact('categories','productsAll','categoryDetails','colorArray','sleeveArray','patternArray','sizesArray','meta_title','meta_description','meta_keywords','url'));
		}


	//filtter function
	public function filter(Request $request)
	{
			$data=$request->all();
			// echo "<pre>"; print_r($data);
			$colorUrl="";
        if(!empty($data['colorFilter'])){
            foreach($data['colorFilter'] as $color){
                if(empty($colorUrl)){
                    $colorUrl = "&color=".$color;
                }else{
                    $colorUrl .= "-".$color;
                }
            }
		}

		$sleeveUrl="";
        if(!empty($data['sleeveFilter'])){
            foreach($data['sleeveFilter'] as $sleeve){
                if(empty($sleeveUrl)){
                    $sleeveUrl = "&sleeve=".$sleeve;
                }else{
                    $sleeveUrl .= "-".$sleeve;
                }
            }
		}
		
		$patternUrl="";
        if(!empty($data['patternFilter'])){
            foreach($data['patternFilter'] as $pattern){
                if(empty($patternUrl)){
                    $patternUrl = "&pattern=".$pattern;
                }else{
                    $patternUrl .= "-".$pattern;
                }
            }
        }

		$sizeUrl="";
        if(!empty($data['sizeFilter'])){
            foreach($data['sizeFilter'] as $size){
                if(empty($sizeUrl)){
                    $sizeUrl = "&size=".$size;
                }else{
                    $sizeUrl .= "-".$size;
                }
            }
        }

        $finalUrl = "products/".$data['url']."?".$colorUrl.$sleeveUrl.$patternUrl.$sizeUrl;
        return redirect::to($finalUrl);
		
		
	}

	//product details page
	public function product($id=null)
	{

		//show 404 page if product is disable
		$productCount=Product::where(['id'=>$id,'status'=>1])->count();
		if($productCount==0)
		{
			abort(404);
		}

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
		
		// echo "<pre>"; print_r($productDetails); die;
		$meta_title = $productDetails->product_name;
        $meta_description = $productDetails->description;
        $meta_keywords = $productDetails->product_name;
		return view('products.detail')->with(compact('productDetails','categories','productAltImages','totla_stock','relatedProducts','meta_title','meta_description','meta_keywords'));

	}

	//get prodiuct price by size
	public function productPrice(Request $request)
	{
		$data=$request->all();
		// echo "<pre>"; print_r($data);
		$proArr=explode("-",$data['idSize']);
		$proAttr=ProductsAttribute::where(['product_id'=>$proArr[0],'size'=>$proArr[1]])->first();
		$getCurrencyRates = Product::getCurrencyRates($proAttr->price);
		echo $proAttr->price."-".$getCurrencyRates['USD_Rate']."-".$getCurrencyRates['GBP_Rate']."-".$getCurrencyRates['EUR_Rate'];
		$stock=$proAttr->stock;
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
	
	public function addtocart(Request $request){

        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        $data = $request->all();
        // echo "<pre>"; print_r($data); die;

        // Check Product Stock is available or not
        $product_size = explode("-",$data['size']);
        $getProductStock = ProductsAttribute::where(['product_id'=>$data['product_id'],'size'=>$product_size[1]])->first();

        if($getProductStock->stock<$data['quantity']){
            return redirect()->back()->with('flash_message_error','Required Quantity is not available!');
        }

        if(empty(Auth::user()->email)){
            $data['user_email'] = '';    
        }else{
            $data['user_email'] = Auth::user()->email;
        }

        $session_id = Session::get('session_id');
        if(!isset($session_id)){
            $session_id = str_random(40);
            Session::put('session_id',$session_id);
        }

        $sizeIDArr = explode('-',$data['size']);
        $product_size = $sizeIDArr[1];

        if(empty(Auth::check())){
            $countProducts = DB::table('cart')->where(['product_id' => $data['product_id'],'product_color' => $data['product_color'],'size' => $product_size,'session_id' => $session_id])->count();
            if($countProducts>0){
                return redirect()->back()->with('flash_message_error','Product already exist in Cart!');
            }
        }else{
            $countProducts = DB::table('cart')->where(['product_id' => $data['product_id'],'product_color' => $data['product_color'],'size' => $product_size,'user_email' => $data['user_email']])->count();
            if($countProducts>0){
                return redirect()->back()->with('flash_message_error','Product already exist in Cart!');
            }    
        }
        

        $getSKU = ProductsAttribute::select('sku')->where(['product_id' => $data['product_id'], 'size' => $product_size])->first();
                
        DB::table('cart')
        ->insert(['product_id' => $data['product_id'],'product_name' => $data['product_name'],
            'product_code' => $getSKU['sku'],'product_color' => $data['product_color'],
            'price' => $data['price'],'size' => $product_size,'quantity' => $data['quantity'],'user_email' => $data['user_email'],'session_id' => $session_id]);

        return redirect('cart')->with('flash_message_success','Product has been added in Cart!');
    }

	public function cart(Request $request)
	{
		if(Auth::check()){
            $user_email = Auth::user()->email;
            $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();     
        }else{
            $session_id = Session::get('session_id');
            $userCart = DB::table('cart')->where(['session_id' => $session_id])->get();    
        }

		foreach($userCart as $key=>$product)
		{
			$productDetails=Product::where('id',$product->product_id)->first();
			$userCart[$key]->image=$productDetails->image;
			
		}

		//  echo "<pre>"; print_r($userCart); die;
		$meta_title = "Shopping Cart - E-com Website";
        $meta_description = "View Shopping Cart of E-com Website";
        $meta_keywords = "shopping cart, e-com Website";
        return view('products.cart')->with(compact('userCart','meta_title','meta_description','meta_keywords'));
	}

	public function deleteCartProduct($id=null){
		Session::forget('CouponAmount');
		Session::forget('CouponCode');
		
		DB::table('cart')->where('id',$id)->delete();
        return redirect('cart')->with('flash_message_success','Product has been deleted in Cart!');

	}
	public function updateCartProductQuantity($id=null,$quantity){

		Session::forget('CouponAmount');
		Session::forget('CouponCode');

		$getProductSKU = DB::table('cart')->select('product_code','quantity')->where('id',$id)->first();
        $getProductStock = ProductsAttribute::where('sku',$getProductSKU->product_code)->first();
        $updated_quantity = $getProductSKU->quantity+$quantity;
        if($getProductStock->stock>=$updated_quantity){
            DB::table('cart')->where('id',$id)->increment('quantity',$quantity); 
            return redirect('cart')->with('flash_message_success','Product Quantity has been updated in Cart!');   
        }else{
            return redirect('cart')->with('flash_message_error','Required Product Quantity is not available!');    
        }
		

	}

	public function applyCoupon(Request $request){

		Session::forget('CouponAmount');
        Session::forget('CouponCode');

		$data = $request->all();
        /*echo "<pre>"; print_r($data); die;*/
        $couponCount = Coupon::where('coupon_code',$data['coupon_code'])->count();
        if($couponCount == 0){
            return redirect()->back()->with('flash_message_error','This coupon does not exists!');
        }else{

			 // Get Coupon Details
			 $couponDetails = Coupon::where('coupon_code',$data['coupon_code'])->first();
			// If coupon is Inactive
			if($couponDetails->status==0){
				return redirect()->back()->with('flash_message_error','This coupon is not active!');
			}
			
			// If coupon is Expired
            $expiry_date = $couponDetails->expiry_date;
            $current_date = date('Y-m-d');
            if($expiry_date < $current_date){
                return redirect()->back()->with('flash_message_error','This coupon is expired!');
			}
			
			 // Coupon is Valid for Discount
			
			 //get cart total ammount
			// Get Cart Total Amount
            if(Auth::check()){
                $user_email = Auth::user()->email;
                $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();     
            }else{
                $session_id = Session::get('session_id');
                $userCart = DB::table('cart')->where(['session_id' => $session_id])->get();    
            }
  
			 $total_amount = 0;
			 foreach($userCart as $item){
				$total_amount = $total_amount + ($item->price * $item->quantity);
			 } 

           // Check if amount type is Fixed or Percentage
            if($couponDetails->amount_type=="Fixed"){
                $couponAmount = $couponDetails->amount;
            }else{
                $couponAmount = $total_amount * ($couponDetails->amount/100);
			}
			
		 // Add Coupon Code & Amount in Session
		 Session::put('CouponAmount',$couponAmount);
		 Session::put('CouponCode',$data['coupon_code']);

		 return redirect()->back()->with('flash_message_success','Coupon code successfully
			 applied. You are availing discount!');

		}
	}


	public function checkout(Request $request){
		$user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::find($user_id);
        $countries = Country::get();


		$shippingCount = DeliveryAddress::where('user_id',$user_id)->count();

		//$shippingDetails variable as array so that Undefined variable error will not come as shown 
        $shippingDetails = array();
        if($shippingCount>0){
            $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();
        }

		// Update cart table with user email
        $session_id = Session::get('session_id');
        DB::table('cart')->where(['session_id'=>$session_id])->update(['user_email'=>$user_email]);

		if($request->isMethod('post')){
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
            // Return to Checkout page if any of the field is empty
            if(empty($data['billing_name']) || empty($data['billing_address']) || empty($data['billing_city']) || empty($data['billing_state']) || empty($data['billing_country']) || empty($data['billing_pincode']) || empty($data['billing_mobile']) || empty($data['shipping_name']) || empty($data['shipping_address']) || empty($data['shipping_city']) || empty($data['shipping_state']) || empty($data['shipping_country']) || empty($data['shipping_pincode']) || empty($data['shipping_mobile'])){
                    return redirect()->back()->with('flash_message_error','Please fill all fields to Checkout!');
			}

		// Update User details
		User::where('id',$user_id)->update(['name'=>$data['billing_name'],'address'=>$data['billing_address'],'city'=>$data['billing_city'],'state'=>$data['billing_state'],'pincode'=>$data['billing_pincode'],'country'=>$data['billing_country'],'mobile'=>$data['billing_mobile']]);
		if($shippingCount>0){
			// Update Shipping Address
			DeliveryAddress::where('user_id',$user_id)->update(['name'=>$data['shipping_name'],'address'=>$data['shipping_address'],'city'=>$data['shipping_city'],'state'=>$data['shipping_state'],'pincode'=>$data['shipping_pincode'],'country'=>$data['shipping_country'],'mobile'=>$data['shipping_mobile']]);
		}else{
			// Add New Shipping Address
			$shipping = new DeliveryAddress;
			$shipping->user_id = $user_id;
			$shipping->user_email = $user_email;
			$shipping->name = $data['shipping_name'];
			$shipping->address = $data['shipping_address'];
			$shipping->city = $data['shipping_city'];
			$shipping->state = $data['shipping_state'];
			$shipping->pincode = $data['shipping_pincode'];
			$shipping->country = $data['shipping_country'];
			$shipping->mobile = $data['shipping_mobile'];
			$shipping->save();
		}
		$pincodeCount = DB::table('pincodes')->where('pincode',$data['shipping_pincode'])->count();
		if($pincodeCount == 0){
			return redirect()->back()->with('flash_message_error','Your location is not available for delivery. Please enter another location.');
		}

		return redirect()->action('ProductController@orderReview');
		}

		return view('products.checkout')->with(compact('userDetails','countries','shippingDetails'));
	}
	

	public function orderReview()
	{
		$user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::where('id',$user_id)->first();
        $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();
        $shippingDetails = json_decode(json_encode($shippingDetails));
        $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();

		foreach($userCart as $key => $product){
            $productDetails = Product::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
            // $total_weight = $total_weight + $productDetails->weight;
        }

		$codpincodeCount = DB::table('cod_pincodes')->where('pincode',$shippingDetails->pincode)->count();
        $prepaidpincodeCount = DB::table('prepaid_pincodes')->where('pincode',$shippingDetails->pincode)->count();

		
		return view('products.order_review')->with(compact('userDetails','shippingDetails','userCart','codpincodeCount','prepaidpincodeCount'));
	}


	public function placeOrder(Request $request)
	{
		if($request->isMethod('post'))
		{
			$data = $request->all();
            $user_id = Auth::user()->id;
			$user_email = Auth::user()->email;
			
			// Get Shipping Address of User
			$shippingDetails = DeliveryAddress::where(['user_email' => $user_email])->first();

			$pincodeCount = DB::table('pincodes')->where('pincode',$shippingDetails->pincode)->count();
            if($pincodeCount == 0){
                return redirect()->back()->with('flash_message_error','Your location is not available for delivery. Please enter another location.');
            }
			
			if(empty(Session::get('CouponCode'))){
				$coupon_code = ''; 
			 }else{
				$coupon_code = Session::get('CouponCode'); 
			 }
 
			 if(empty(Session::get('CouponAmount'))){
				$coupon_amount = ''; 
			 }else{
				$coupon_amount = Session::get('CouponAmount'); 
			 }

			$order = new Order;
            $order->user_id = $user_id;
            $order->user_email = $user_email;
            $order->name = $shippingDetails->name;
            $order->address = $shippingDetails->address;
            $order->city = $shippingDetails->city;
            $order->state = $shippingDetails->state;
            $order->pincode = $shippingDetails->pincode;
            $order->country = $shippingDetails->country;
            $order->mobile = $shippingDetails->mobile;
            $order->coupon_code = $coupon_code;
            $order->coupon_amount = $coupon_amount;
            $order->order_status = "New";
            $order->payment_method = $data['payment_method'];
            $order->shipping_charges ="";
            $order->grand_total = $data['grand_total'];
			$order->save();
			

			$order_id = DB::getPdo()->lastInsertId();

            $cartProducts = DB::table('cart')->where(['user_email'=>$user_email])->get();
            foreach($cartProducts as $pro){
                $cartPro = new OrdersProduct;
                $cartPro->order_id = $order_id;
                $cartPro->user_id = $user_id;
                $cartPro->product_id = $pro->product_id;
                $cartPro->product_code = $pro->product_code;
                $cartPro->product_name = $pro->product_name;
                $cartPro->product_color = $pro->product_color;
                $cartPro->product_size = $pro->size;
                $cartPro->product_price = $pro->price;
                $cartPro->product_qty = $pro->quantity;
                $cartPro->save();

			}
			Session::put('order_id',$order_id);
			Session::put('grand_total',$data['grand_total']);

			if($data['payment_method']=="COD")
			{

				
			
				$productDetails = Order::with('orders')->where('id',$order_id)->first();
                $productDetails = json_decode(json_encode($productDetails),true);
                /*echo "<pre>"; print_r($productDetails);*/ /*die;*/

                $userDetails = User::where('id',$user_id)->first();
                $userDetails = json_decode(json_encode($userDetails),true);
                /*echo "<pre>"; print_r($userDetails); die;*/
                /* Code for Order Email Start */
                $email = $user_email;
                $messageData = [
                    'email' => $email,
                    'name' => $shippingDetails->name,
                    'order_id' => $order_id,
                    'productDetails' => $productDetails,
                    'userDetails' => $userDetails
                ];
                Mail::send('emails.order',$messageData,function($message) use($email){
                    $message->to($email)->subject('Order Placed - E-com Website');    
                });
                /* Code for Order Email Ends */



				// COD - Redirect user to thanks page after saving order
				return redirect('/thanks');
			}else {
				// COD - Redirect user to paypal page after saving order
				return redirect('/paypal');
			}


			
		}
	}


	public function thanks(Request $request){
        $user_email = Auth::user()->email;
        DB::table('cart')->where('user_email',$user_email)->delete();
        return view('orders.thanks');
	}
	
	public function userOrders()
	{
		$user_id = Auth::user()->id;
        $orders = Order::with('orders')->where('user_id',$user_id)->orderBy('id','DESC')->get();
        /*$orders = json_decode(json_encode($orders));
        echo "<pre>"; print_r($orders); die;*/
		return view('orders.user_orders')->with(compact('orders'));
	}
	
	public function userOrderDetails($order_id){
        $user_id = Auth::user()->id;
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));
        /*echo "<pre>"; print_r($orderDetails); die;*/
        return view('orders.user_order_details')->with(compact('orderDetails'));
	}
	
	public function paypal(){
		$user_email = Auth::user()->email;
        DB::table('cart')->where('user_email',$user_email)->delete();
        return view('orders.paypal');
	}

	public function thanksPaypal(){
        return view('orders.thanks_paypal');
    }

    

    public function cancelPaypal(){
        return view('orders.cancel_paypal');
	}
	
	public function viewOrders(){
        $orders = Order::with('orders')->orderBy('id','Desc')->get();
        $orders = json_decode(json_encode($orders));
        /*echo "<pre>"; print_r($orders); die;*/
        return view('admin.orders.view_orders')->with(compact('orders'));
	}
	
	public function viewOrderDetails($order_id){
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));
        /*echo "<pre>"; print_r($orderDetails); die;*/
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id',$user_id)->first();
        /*$userDetails = json_decode(json_encode($userDetails));
        echo "<pre>"; print_r($userDetails);*/
        return view('admin.orders.order_details')->with(compact('orderDetails','userDetails'));
	}
	

	public function updateOrderStatus(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            Order::where('id',$data['order_id'])->update(['order_status'=>$data['order_status']]);
            return redirect()->back()->with('flash_message_success','Order Status has been updated successfully!');
        }
    }

	public function viewOrderInvoice($order_id){
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));
        /*echo "<pre>"; print_r($orderDetails); die;*/
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id',$user_id)->first();
        /*$userDetails = json_decode(json_encode($userDetails));
        echo "<pre>"; print_r($userDetails);*/
        return view('admin.orders.order_invoice')->with(compact('orderDetails','userDetails'));
    }


	public function searchProducts(Request $request)
	{
		if($request->isMethod('post')){
			$data = $request->all();
			
			
            $categories = Category::with('categories')->where(['parent_id' => 0])->get();
            $search_product = $data['product'];
            /*$productsAll = Product::where('product_name','like','%'.$search_product.'%')->orwhere('product_code',$search_product)->where('status',1)->paginate();*/

            $productsAll = Product::where(function($query) use($search_product){
                $query->where('product_name','like','%'.$search_product.'%')
                ->orWhere('product_code','like','%'.$search_product.'%')
                ->orWhere('description','like','%'.$search_product.'%')
                ->orWhere('product_color','like','%'.$search_product.'%');
            })->where('status',1)->get();
			// echo $productsAll;
            // $breadcrumb = "<a href='/'>Home</a> / ".$search_product;

            return view('products.listing')->with(compact('categories','productsAll','search_product')); 
        }
	}

	public function checkPincode(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            echo $pincodeCount = DB::table('pincodes')->where('pincode',$data['pincode'])->count();
        }
    }
}

