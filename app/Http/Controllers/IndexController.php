<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        //in Ascending order
        $productsAll=Product::get();

        //in dscending order
        $productsAll=Product::orderBy('id','DESC')->get();

        //in random order
        $productsAll=Product::inRandomorder()->get();

        //get category and sub cetgory
        $categories=Category::with('categories')->where(['parent_id'=>0])->get();
       

        return view('index')->with(compact('productsAll','categories'));
    }
}
