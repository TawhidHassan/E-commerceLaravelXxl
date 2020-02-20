<?php

namespace App\Http\Controllers;


use App\User;
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
    public function checkPasd(Request $request){
        $data = $request->all();
        $current_password = $data['current_pwd'];
        $check_password =Auth::user()->first();
        if(Hash::check($current_password,$check_password->password)){
            echo "true"; die;
        }else {
            echo "false"; die;
        }
    }

    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $check_password = Auth::user()->first();
            $current_password = $data['current_pwd'];
            if(Hash::check($current_password,$check_password->password)){
                $password = bcrypt($data['new_pwd']);
                User::where('id','1')->update(['password'=>$password]);
                return redirect('/admin/setting')->with('flash_message_success','Password updated Successfully!');
            }else {
                return redirect('/admin/setting')->with('flash_message_error','Incorrect Current Password!');
            }
        }
    }
}
