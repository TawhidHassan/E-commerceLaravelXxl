<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.admin_login');
    }

    public function dashboard()
    {
        return view('admin.dashbord');
    }
    public function setting()
    {
        return view('admin.setting');
    }
    public function checkPasd(Request $request)
    {
        $data=$request->all();
        $current_password=$data['old_pasd'];
        $check_pas=Auth::user();
        if (Hash::check($current_password,$check_pas->password))
        {
            echo "true";die;
        }else{
            echo "false";die;
        }

    }
}
