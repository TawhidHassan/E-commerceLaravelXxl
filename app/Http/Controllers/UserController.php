<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

                $user = new User;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                date_default_timezone_set('Asia/Dhaka');
                $user->created_at = date("Y-m-d H:i:s");
                $user->updated_at = date("Y-m-d H:i:s");
                $user->save();

                if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
                    Session::put('frontSession',$data['email']);
                    return redirect('/cart');
                }
               
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


    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
            if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
                Session::put('frontSession',$data['email']);
                return redirect('/cart');
            }else{
                return redirect()->back()->with('flash_message_error','Invalid Username or Password!');
            }
        }
    }

    public function logout(){
        Auth::logout();
        Session::forget('frontSession');
        return redirect('/');
    }

    public function account(){
       
        return view('users.account');
    }


}
