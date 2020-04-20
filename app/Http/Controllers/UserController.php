<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userLoginRegister(){
        $meta_title = "User Login/Register - Ecom Website";
        return view('users.login_register')->with(compact('meta_title'));    
    }
}
