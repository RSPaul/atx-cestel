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
Route::get('/profile', 'User\UserController@profile');
Route::post('/book', 'HomeController@book');
