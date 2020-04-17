<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     coming soon
// });

//home poage
Route::get('/','IndexController@index');



Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

//category/listing page
Route::get('/products/{url}', 'ProductController@products');

//product details page
Route::get('/product/{url}', 'ProductController@product');

//Add to cart page
Route::match(['get','post'],'/add-cart','ProductController@addToCart');

//product add to cart page
Route::match(['get','post'],'/cart','ProductController@cart');
//delete cart
Route::get('/cart/delete-product/{id}', 'ProductController@deleteCartProduct');
//update product quantity from cart page
Route::get('/cart/update-quantity/{id}/{quantity}', 'ProductController@updateCartProductQuantity');

//product details page get product price by size
Route::get('/get-product-price', 'ProductController@productPrice');

// Apply Coupon
Route::post('/cart/apply-coupon','ProductController@applyCoupon');


Route::get('/admin','AdminController@login')->name('admin.login');


Route::group(['middleware'=>['auth','admin']], function () {

    Route::get('/admin/dashboard','AdminController@dashboard')->name('admin.dashbord');
    Route::get('/admin/setting','AdminController@setting')->name('admin.setting');
    Route::get('/admin/check-pwd','AdminController@checkPasd')->name('admin.checkPasd');
    Route::post('/admin/updatepassword','AdminController@updatePassword')->name('admin.updatePassword');

    // Categories Routes (Admin)
    Route::match(['get','post'],'/admin/add-category','CategoryController@addCategory');
    Route::match(['get','post'],'/admin/edit-category/{id}','CategoryController@editCategory');
    Route::match(['get','post'],'/admin/delete-category/{id}','CategoryController@deleteCategory');
    Route::get('/admin/view-categories','CategoryController@viewCategories');

    // Products Routes
    Route::match(['get','post'],'/admin/add-product','ProductController@addProduct');
    Route::match(['get', 'post'], '/admin/edit-product/{id}','ProductController@editProduct');
    Route::get('/admin/view-products','ProductController@viewProducts');
    
	Route::get('/admin/delete-product-image/{id}','ProductController@deleteProductImage');
    Route::get('/admin/delete-product/{id}','ProductController@deleteProduct');
    
    // product attributes
    Route::match(['get','post'],'/admin/add-attributes/{id}','ProductController@addAttributes');
    Route::match(['get', 'post'], '/admin/edit-attributes/{id}','ProductController@editAttributes');
    Route::get('/admin/delete-attribute/{id}','ProductController@deleteAttributes');

     // product attributes
     Route::match(['get','post'],'/admin/add-images/{id}','ProductController@addImages');
     Route::get('/admin/delete-alt-image/{id}','ProductController@deleteProductAltImage');

     //for coupons
     Route::match(['get','post'],'/admin/add-coupon','CouponsController@addCoupon');
     Route::match(['get','post'],'/admin/edit-coupon/{id}','CouponsController@editCoupon');
	 Route::get('/admin/view-coupons','CouponsController@viewCoupons');
	 Route::get('/admin/delete-coupon/{id}','CouponsController@deleteCoupon');



});
