<?php

namespace App\Http\Controllers;


use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function viewCategories()
    {
    
        $categories=Category::get();
        $categories=json_decode(json_encode($categories));
        return view('admin.categories.view_categories')->with(compact('categories'));
    }


    public function addCategory(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();

            $category = new Category;
            $category->name = $data['category_name'];
            $category->parent_id = 1;
            $category->status =1;
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->save();
            return redirect('/admin/view-categories')->with('flash_message_success','Category added Successfully!');
        }

//        $levels = Category::where(['parent_id'=>0])->get();
        return view('admin.categories.add_category');
    }


    public function deleteCategory(Request $request, $id = null){
        if(!empty($id)){
            Category::where(['id'=>$id])->delete();
            return redirect()->back()->with('flash_message_success','Category deleted Successfully!');
        }
    }
  
}
