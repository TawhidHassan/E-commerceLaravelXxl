<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userLoginRegister(){
        $meta_title = "User Login/Register - Ecom Website";
        return view('users.login_register')->with(compact('meta_title'));    
    }


    public function register(Request $request){
    	if($request->isMethod('post')){
    		$data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
    		// Check if User already exists
    		$usersCount = User::where('email',$data['email'])->count();
    		if($usersCount>0){
    			return redirect()->back()->with('flash_message_error','Email already exists!');
    		}else{

                return redirect()->back()->with('flash_message_success','Please confirm your email to activate your account!');               
    		}	
    	}
    }


    public function checkEmail(Request $request)
    {
    // Check if User already exists
    $data = $request->all();
    $usersCount = User::where('email',$data['email'])->count();
    if($usersCount>0){
        echo "false";
    }else{
        echo "true"; die;
    }		
    }
}
