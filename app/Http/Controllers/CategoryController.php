<?php

namespace App\Http\Controllers;


use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
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

        }

//        $levels = Category::where(['parent_id'=>0])->get();
        return view('admin.categories.add_category');
    }
    public function saveCategory(Request $request)
    {

            $category = new Category;
            $category->name = $request->category_name;
            $category->parent_id = 1;
            $category->status = 1;
            $category->description = $request->description;
            $category->url = $request->url;
            $category->save();
            return view('admin.categories.add_category');

    }
}
