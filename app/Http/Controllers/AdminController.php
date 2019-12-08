<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
