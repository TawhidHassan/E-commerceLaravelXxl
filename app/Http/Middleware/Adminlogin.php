<?php

namespace App\Http\Middleware;

use Closure;
use App\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;

class Adminlogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(empty(Session::has('adminSession')))
        {
            return redirect('/admin');
        }
        else{
            //get admin/sub admin details
            $adminDetails = Admin::where('name',Session::get('adminSession'))->first();
            $adminDetails = json_decode(json_encode($adminDetails),true);
            // echo "<pre>"; print_r($adminDetails); die;
            if($adminDetails['type']=="Admin"){
                $adminDetails['categories_access']=1; 
                $adminDetails['products_access']=1; 
                $adminDetails['orders_access']=1; 
                $adminDetails['users_access']=1; 
            }
            $request->session()->put('adminDetails', $adminDetails);
            //  echo "<pre>"; print_r(Session::get('adminDetails')); die;

            // Get Current Path
            $currentPath= Route::getFacadeRoot()->current()->uri();
            if($currentPath=="admin/view-categories" && Session::get('adminDetails')['categories_access']==0){
                return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
            }

            if($currentPath=="admin/view-products" && Session::get('adminDetails')['products_access']==0){
                return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
            }

            if($currentPath=="admin/add-product" && Session::get('adminDetails')['products_access']==0){
                return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
            }
            if($currentPath=="admin/view-orders" && Session::get('adminDetails')['orders_access']==0){
                return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
            }
            if($currentPath=="admin/view-users" && Session::get('adminDetails')['users_access']==0){
                return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
            }
            if($currentPath=="admin/view-admins" && Session::get('adminDetails')['users_access']==0){
                return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
            }
            if($currentPath=="admin/add-admin" && Session::get('adminDetails')['users_access']==0){
                return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
            }

        }
        return $next($request);
    }
}
