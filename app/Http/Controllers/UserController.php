<?php

namespace App\Http\Controllers;
use App\User;
use App\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function account(Request $request){
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);
        $countries = Country::get();
        if($request->isMethod('post')){
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/

            if(empty($data['name'])){
                return redirect()->back()->with('flash_message_error','Please enter your Name to update your account details!');    
            }

            if(empty($data['address'])){
                $data['address'] = '';    
            }

            if(empty($data['city'])){
                $data['city'] = '';    
            }

            if(empty($data['state'])){
                $data['state'] = '';    
            }

            if(empty($data['country'])){
                $data['country'] = '';    
            }

            if(empty($data['pincode'])){
                $data['pincode'] = '';    
            }

            if(empty($data['mobile'])){
                $data['mobile'] = '';    
            }

            $user = User::find($user_id);
            $user->name = $data['name'];
            $user->address = $data['address'];
            $user->city = $data['city'];
            $user->state = $data['state'];
            $user->country = $data['country'];
            $user->pincode = $data['pincode'];
            $user->mobile = $data['mobile'];
            $user->save();
            return redirect()->back()->with('flash_message_success','Your account details has been successfully updated!');
        }


        return view('users.account')->with(compact('countries','userDetails'));
    }

    public function chkUserPassword(Request $request){
        $data = $request->all();
        /*echo "<pre>"; print_r($data); die;*/
        $current_password = $data['current_pwd'];
        $user_id = Auth::User()->id;
        $check_password = User::where('id',$user_id)->first();
        if(Hash::check($current_password,$check_password->password)){
            echo "true"; die;
        }else{
            echo "false"; die;
        }
    }

    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
            $old_pwd = User::where('id',Auth::User()->id)->first();
            $current_pwd = $data['current_pwd'];
            if(Hash::check($current_pwd,$old_pwd->password)){
                // Update password
                $new_pwd = bcrypt($data['new_pwd']);
                User::where('id',Auth::User()->id)->update(['password'=>$new_pwd]);
                return redirect()->back()->with('flash_message_success',' Password updated successfully!');
            }else{
                return redirect()->back()->with('flash_message_error','Current Password is incorrect!');
            }
        }
    }

}
