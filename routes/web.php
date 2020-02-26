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
Route::get('/be-part-team', 'HomeController@bePartTeam')->name('viewpartTeam');
Route::post('/be-part-team', 'HomeController@bePartTeam')->name('partTeam');
Route::get('/verify', 'Auth\VerificationController@verify');
Route::get('/email/resend', 'Auth\VerificationController@resend');
Route::post('/register/user', 'HomeController@register')->name('register_user');
Auth::routes(['verify' => true]);

/**
* Generic Routes
**/
Route::get('/user-profile/{id}', 'User\UserController@get')->name('user_by_id');
Route::post('/user/upload/profile', 'User\UserController@uploadPicture')->name('upload_profile_picture');
Route::post('/update-profile', 'User\UserController@updateProile')->name('update_proile');

/**
* User Routes
**/
Route::get('/profile', 'User\UserController@profile')->name('user_profile');
Route::get('/dashbaord', 'User\UserController@profile')->name('user_dashboard');

Route::post('/book', 'HomeController@book')->name('booking');
Route::post('/booking/checkout', 'HomeController@book')->name('booking_checkout');
Route::get('/user/{tab}', 'User\UserController@dashboard')->name('user_dashboard');
Route::get('/user-schedule', 'User\UserController@schedule')->name('user_schedule');
Route::get('/user-view-schedule', 'User\UserController@viewscheduleList')->name('viewscheduleList');
Route::post('/cancel-booking-amount', 'User\UserController@cancelBookingAmount')->name('cancel_booking_amount');
Route::post('/cancel-booking', 'User\UserController@cancelBooking')->name('cancel_booking');
Route::post('/complete-booking', 'User\UserController@completeBooking')->name('complete_booking');


/**
* Laundress Routes
**/
Route::get('/laundress/{tab}', 'Laundress\LaundressController@dashboard')->name('laundress_dashboard');
Route::get('/laundress-schedule', 'Laundress\LaundressController@schedule')->name('laundress_schedule');
Route::get('/laundress-view-schedule', 'Laundress\LaundressController@viewscheduleList')->name('viewscheduleList');
Route::get('/earnings-by-week', 'Laundress\LaundressController@earningsByWeek')->name('earningsByWeek');
Route::post('/decline-booking', 'Laundress\LaundressController@declineBooking')->name('decline_booking');


/**
* Admin Routes
**/
Route::get('/admin', 'Admin\AdminController@dashboard')->name('admin_dashboard');
Route::get('/users/{type}', 'Admin\AdminController@users');
Route::get('/admin/bookings', 'Admin\AdminController@bookings');
Route::get('/admin/bookings/{id}', 'Admin\AdminController@bookingDetails');
Route::get('/user/verify/{id}', 'Admin\AdminController@verifyUser')->name('verify_user');