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
Route::get('/verify/email/{token}', 'Auth\VerificationController@verifyEmail');
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
Route::post('/user-view-schedule-custom', 'User\UserController@viewscheduleListCustom')->name('viewscheduleListCustom');

/**
* Laundress Routes
**/
Route::post('/laundress/upload/profile', 'Laundress\LaundressController@uploadPicture');
Route::get('/laundress/{tab}', 'Laundress\LaundressController@dashboard')->name('laundress_dashboard');
Route::get('/laundress-schedule', 'Laundress\LaundressController@schedule')->name('laundress_schedule');
Route::get('/laundress-view-schedule', 'Laundress\LaundressController@viewscheduleList')->name('viewscheduleList');
Route::post('/laundress-view-schedule-custom', 'Laundress\LaundressController@viewscheduleListCustom')->name('viewscheduleListCustom');
Route::get('/earnings-by-week', 'Laundress\LaundressController@earningsByWeek')->name('earningsByWeek');
Route::post('/decline-booking', 'Laundress\LaundressController@declineBooking')->name('decline_booking');
Route::post('/update-account', 'Laundress\LaundressController@updateAccount')->name('update_account');
Route::get('/get-account', 'Laundress\LaundressController@getAccount')->name('get_account');
Route::post('/request-payment', 'Laundress\LaundressController@requestPayment')->name('request_payment');


/**
* Admin Routes
**/
Route::get('/admin', 'Admin\AdminController@dashboard')->name('admin_dashboard');
Route::get('/users/{type}', 'Admin\AdminController@users');
Route::get('/bookings/{type}', 'Admin\AdminController@bookings');
Route::get('/booking/{id}', 'Admin\AdminController@bookingDetails');
Route::get('/user/verify/{id}', 'Admin\AdminController@verifyUser')->name('verify_user');
Route::get('/payments/{type}', 'Admin\AdminController@payments')->name('payments');
Route::get('/payment/{id}', 'Admin\AdminController@viewPayment')->name('view_payment');
Route::get('/admin/confirm-payment/{id}', 'Admin\AdminController@confirmPayment')->name('admin_confirm_ayment');