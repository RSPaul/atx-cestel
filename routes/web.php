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

Route::get('/book', 'HomeController@book');
Route::get('/verify', 'Auth\VerificationController@verify');
Route::get('/email/resend', 'Auth\VerificationController@resend');
Route::post('/register/user', 'HomeController@register')->name('register_user');
Auth::routes(['verify' => true]);
Route::get('/profile', 'User\UserController@profile')->name('user_profile');
Route::get('/dashbaord', 'User\UserController@profile')->name('user_dashboard');
Route::get('/user/{id}', 'User\UserController@get')->name('user_by_id');
Route::post('/book', 'HomeController@book')->name('booking');
Route::post('/booking/checkout', 'HomeController@book')->name('booking_checkout');
Route::post('/user/upload/profile', 'User\UserController@uploadPicture')->name('upload_profile_picture');
