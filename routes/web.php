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

use App\Notifications\AccountVerificationNotification;
use App\UserToken;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('phpinfo', function() {
	return phpinfo();
});

Route::group(['prefix' => 'user/account/'], function() {
    Route::get('verify/{token}', 'Auth\RegisterController@verify')->name('verify');
    Route::post('resend/{id}', 'Auth\RegisterController@resend')->name('resend');
});

// Pages route
Route::get('couriers', 'CourierController@page')->name('couriers');
Route::get('lots/categories', 'CategoryController@page')->name('lots.categories');
Route::get('lots', 'LotController@page')->name('lots');
Route::get('products', 'ProductController@page')->name('products');
Route::get('inbounds', 'InboundController@page')->name('inbounds');
Route::get('outbounds', 'OutboundController@page')->name('outbounds');
Route::get('purchase', 'PaymentController@page')->name('payment');
Route::get('users', 'UserController@page')->name('users');
Route::get('profile', 'UserController@page')->name('profile');
Route::get('dashboard', 'AdminController@page')->name('dashboard');

Route::post('lots/products/update', 'LotController@editStock');

/* Route for Socialite authentication */
Route::group(['prefix' => 'auth', 'as' => 'auth.social.'], function() {
    Route::get('social/{provider}', ['as' => 'redirect', 'uses'=> 'Auth\SocialController@redirectToProvider']);
    Route::get('social/{provider}/callback', ['as' => 'callback', 'uses'=> 'Auth\SocialController@handleProviderCallback']);
});


Route::group(['prefix' => 'lot'], function() {
	Route::get('index', 'LotController@index');
	Route::get('delete/{id}', 'LotController@destroy');
	Route::post('store', 'LotController@store');
	Route::post('update', 'LotController@update');
	Route::post('assign/{lot}', 'LotController@assign');
	Route::post('unassign/{lot}', 'LotController@unassign');
});

Route::get('categories', 'CategoryController@page')->name('lots.categories');

Route::group(['prefix' => 'category'], function() {
	Route::get('index', 'CategoryController@index');
	Route::get('delete/{id}', 'CategoryController@destroy');
	Route::post('store', 'CategoryController@store');
	Route::post('update', 'CategoryController@update');
});

Route::group(['prefix' => 'product'], function() {
	Route::get('index', 'ProductController@index');
	Route::get('delete/{id}', 'ProductController@destroy');
	Route::post('store', 'ProductController@store');
	Route::post('update', 'ProductController@update');
});

Route::group(['prefix' => 'internal'], function() {
	Route::get('categories', 'CategoryController@index');
	Route::get('lots', 'LotController@index');
	Route::get('couriers', 'CourierController@index');
	Route::get('products/selector', 'ProductController@selector');
	Route::get('products', 'ProductController@index');
	Route::get('inbound/user', 'InboundController@index');
	Route::get('inbounds/today', 'InboundController@indexToday');
	Route::get('outbound/user', 'OutboundController@index');
	Route::get('inbound/admin', 'Admin\InboundController@index');
	Route::get('outbounds/pending', 'OutboundController@indexPending');
	Route::get('outbound/admin', 'Admin\OutboundController@index');
	Route::get('outbound/{outbound}', 'OutboundController@show');
	Route::get('payments', 'PaymentController@index');
	Route::get('payments/pending', 'PaymentController@indexPending');
	Route::get('payment/{lot}', 'PaymentController@show');
	Route::get('users/list', 'UserController@page');
	Route::get('users/selector', 'UserController@selector');
	Route::get('users', 'UserController@index');
	Route::get('user', 'UserController@show');
	Route::get('user/{user}/lots', 'UserController@lotSelector');
	Route::delete('products/{product}', 'ProductController@destroy');
});

Route::group(['prefix' => 'courier'], function() {
	Route::get('index', 'CourierController@index');
	Route::get('delete/{id}', 'CourierController@destroy');
	Route::post('store', 'CourierController@store');
	Route::post('update', 'CourierController@update');
});

Route::group(['prefix' => 'inbound'], function() {
	Route::get('index', 'InboundController@index');
	Route::get('show/{id}', 'InboundController@show');
	Route::get('delete/{id}', 'InboundController@destroy');
	Route::post('store', 'InboundController@store');
	Route::post('update', 'InboundController@update');
});

Route::group(['prefix' => 'admin'], function() {
	Route::group(['prefix' => 'inbound'], function() {
		Route::get('index', 'Admin\InboundController@index');
		Route::post('update', 'Admin\InboundController@update');
	});
	Route::group(['prefix' => 'outbound'], function() {
		Route::post('update', 'Admin\OutboundController@update');
	});
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::group(['prefix' => 'setting'], function() {
        Route::get('index', 'SettingsController@index')->name('setting.index');
        Route::post('update', 'SettingsController@update')->name('setting.update');
    });
});

Route::group(['prefix' => 'outbound'], function() {
    Route::get('index', 'OutboundController@index')->name('outbound.index');
    Route::post('store', 'OutboundController@store')->name('outbound.store');
});

Route::group(['prefix' => 'download'], function () {
    Route::get('inbound/report/{id}', 'InboundController@report')->name('download.inbound.report');
    Route::get('outbound/report/{id}', 'OutboundController@report')->name('download.outbound.report');
});

Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => 'lot'], function() {
        Route::get('index', 'LotController@index');
        Route::get('delete/{id}', 'LotController@destroy');
        Route::post('store', 'LotController@store');
        Route::post('update', 'LotController@update');
    });
});

Route::group(['prefix' => 'payment'], function() {
    Route::get('index', 'PaymentController@index')->name('payment.index');
    Route::post('store', 'PaymentController@store')->name('payment.store');
    Route::post('approve', 'PaymentController@approve')->name('payment.approve');
    Route::post('purchase', 'PaymentController@purchase')->name('payment.purchase');
});


Route::group(['prefix' => 'user'], function(){
	Route::post('update', 'UserController@update')->name('user.update');
});