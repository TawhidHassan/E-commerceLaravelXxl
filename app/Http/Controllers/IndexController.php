<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $productsAll=Product::orderBy('id','DESC')->get();
        return view('index')->with(compact('productsAll'));
    }
}
