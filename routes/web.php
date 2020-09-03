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
Route::get('/products/{url}', 'ProductController@products')->name('listingProduct');

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

//admin login
Route::match(['get', 'post'], '/admin','AdminController@login');

// Users Login/Register Page
Route::get('/login-register','UserController@userLoginRegister');

//check if user already exists
Route::match(['get','post'],'/check-email','UserController@checkEmail');

// Users Register Form Submit
Route::post('/user-register','UserController@register');

// Users Login Form Submit
Route::post('user-login','UserController@login');

// Users logout
Route::get('/user-logout','UserController@logout'); 

// Forgot Password
Route::match(['get', 'post'],'/forgot-password','UserController@forgot_password');

// Search Productss
Route::post('/search-products','ProductController@searchProducts');

// Products Filters Route
Route::match(['get', 'post'],'/products-filter', 'ProductController@filter');

// Confirm Account
Route::get('confirm/{code}','UserController@confirmAccount');

// Check Subscriber Email
Route::post('/check-subscriber-email','NewsletterController@checkSubscriber');

// Add Subscriber Email
Route::post('/add-subscriber-email','NewsletterController@addSubscriber');


///to prevent all route after login
Route::group(['middleware' => ['frontlogin']], function () {
    // Users Accounts
    Route::match(['get', 'post'], '/account','UserController@account'); 

    //current password check
    Route::post('/check-user-pwd','UserController@chkUserPassword');
    // Update User Password
    Route::post('/update-user-pwd','UserController@updatePassword');

    //checkout
    Route::match(['get','post'],'checkout','ProductController@checkout');

    //oreder view page
    Route::match(['get','post'],'/order-review','ProductController@orderReview');
    // Place Order
    Route::match(['get','post'],'/place-order','ProductController@placeOrder');
    // Thanks Page
    Route::get('/thanks','ProductController@thanks');
    // Users Orders Page
    Route::get('/orders','ProductController@userOrders');
    // User Ordered Products Details
    Route::get('/orders/{id}','ProductController@userOrderDetails');

    // Paypal Page
    Route::get('/paypal','ProductController@paypal');

    // Paypal Thanks Page
	Route::get('/paypal/thanks','ProductController@thanksPaypal');
	// Paypal Cancel Page
	Route::get('/paypal/cancel','ProductController@cancelPaypal');
});

Route::group(['middleware'=>['adminlogin']], function () {

    Route::get('/admin/dashboard','AdminController@dashboard')->name('admin.dashbord');
    Route::get('/admin/setting','AdminController@setting')->name('admin.setting');
    Route::get('/admin/check-pwd','AdminController@chkPassword');
    Route::match(['get', 'post'],'/admin/update-pwd','AdminController@updatePassword');
    Route::get('/logout','AdminController@logout');
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

	// Admin Banners Routes
	Route::match(['get','post'],'/admin/add-banner','BannersController@addBanner');
	Route::match(['get','post'],'/admin/edit-banner/{id}','BannersController@editBanner');
	Route::get('admin/view-banners','BannersController@viewBanners');
	Route::get('/admin/delete-banner/{id}','BannersController@deleteBanner');

    // Admin Orders Routes
    Route::get('/admin/view-orders','ProductController@viewOrders');
    
    // User Ordered Products Details
    Route::get('/admin/view-order/{id}','ProductController@viewOrderDetails');

    // Update Order Status
    Route::post('/admin/update-order-status','ProductController@updateOrderStatus'); 

    // Order Invoice
    Route::get('/admin/view-order-invoice/{id}','ProductController@viewOrderInvoice');

    // Admin Users Route 
    Route::get('/admin/view-users','UserController@viewUsers');

    // Add CMS Route
    Route::match(['get','post'],'/admin/add-cms-page','CmsController@addCmsPage');
    // View CMS Pages
    Route::get('/admin/view-cms-pages','CmsController@viewCmsPages');
    // Edit CMS Page
    Route::match(['get','post'],'/admin/edit-cms-page/{id}','CmsController@editCmsPage');
    // Delete CMS Route
    Route::get('/admin/delete-cms-page/{id}','CmsController@deleteCmsPage'); 

    //product video delete
    Route::get('/admin/delete-product-video/{id}','ProductController@deleteProductVideo');

    //Create GET/POST route for add-currency in web.php file like below :-
    Route::match(['get','post'],'/admin/add-currency','CurrencyController@addCurrency');
    //Create GET route for view-currencies in web.php file like below :-
    Route::get('/admin/view-currencies','CurrencyController@viewCurrencies');
    //Create GET/POST route for edit-currency with id parameter in web.php file like below :-
    Route::match(['get','post'],'/admin/edit-currency/{id}','CurrencyController@editCurrency');
    Route::match(['get','post'],'/admin/delete-currency/{id}','CurrencyController@deleteCurrency');


    // View Shipping Charges
	Route::get('/admin/view-shipping','ShippingController@viewShipping');

	// Update Shipping Charges
    Route::match(['get','post'],'/admin/edit-shipping/{id}','ShippingController@editShipping');
    
    // Admins/Sub-Admins View Route
   Route::get('/admin/view-admins','AdminController@viewAdmins');
   // Add Admins/Sub-Admins Route
   Route::match(['get','post'],'/admin/add-admin','AdminController@addAdmin');
   // Edit Admins/Sub-Admins Route
  Route::match(['get','post'],'/admin/edit-admin/{id}','AdminController@editAdmin');

  // View Newsletter Subscribers
	Route::get('admin/view-newsletter-subscribers','NewsletterController@viewNewsletterSubscribers');

	// Update Newsletter Status
	Route::get('admin/update-newsletter-status/{id}/{status}','NewsletterController@updateNewsletterStatus');

	// Delete Newsletter Email
	Route::get('admin/delete-newsletter-email/{id}','NewsletterController@deleteNewsletterEmail');

	// Export Newsletter Emails
	Route::get('/admin/export-newsletter-emails','NewsletterController@exportNewsletterEmails');

});


// Display CMS Page
Route::match(['get','post'],'/page/{url}','CmsController@cmsPage');
//contect pages
Route::match(['GET','POST'],'/contact','CmsController@contact');

// Check Pincode
Route::post('/check-pincode','ProductController@checkPincode');

