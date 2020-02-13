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

Route::get('/', function () {
    return view('welcome');
});

/**
* Public Routes
**/
Route::get('/book', 'HomeController@book');
Route::get('/be-part-team', 'HomeController@bePartTeam');
Route::post('/be-part-team', 'HomeController@bePartTeam')->name('partTeam');
Route::get('/verify', 'Auth\VerificationController@verify');
Route::get('/email/resend', 'Auth\VerificationController@resend');
Route::post('/register/user', 'HomeController@register')->name('register_user');
Auth::routes(['verify' => true]);

/**
* Generic Routes
**/
Route::get('/user/{id}', 'User\UserController@get')->name('user_by_id');
Route::post('/user/upload/profile', 'User\UserController@uploadPicture')->name('upload_profile_picture');

/**
* Customer Routes
**/
Route::get('/profile', 'User\UserController@profile')->name('user_profile');
Route::get('/dashbaord', 'User\UserController@profile')->name('user_dashboard');

Route::post('/book', 'HomeController@book')->name('booking');
Route::post('/booking/checkout', 'HomeController@book')->name('booking_checkout');

/**
* Laundress Routes
**/



/**
* Admin Routes
**/
Route::get('/admin', 'Admin\AdminController@dashboard')->name('admin_dashboard');
Route::get('/users/{type}', 'Admin\AdminController@users');
Route::get('/user/verify/{id}', 'Admin\AdminController@verifyUser')->name('verify_user');