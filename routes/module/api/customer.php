<?php

use Illuminate\Support\Facades\Route;
use App\Constants\Roles;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'customer'], function () {
    Route::post('login-by-email', 'Customer\LoginController@loginEmail')->name('customer.login.email');
    Route::post('login-by-mobile', 'Customer\LoginController@loginMobile')->name('customer.login.mobile');
    Route::post('signup', 'Customer\LoginController@signup')->name('customer.signup');
});

Route::group(['prefix' => 'customer', 'middleware' => ['auth:api', 'scopes:verification']], function () {
    Route::post('verify-otp', 'Customer\VerifyOtpController@verifyOtp')->name('customer.verify.otp');
    Route::get('resend-otp', 'Customer\VerifyOtpController@resendOtp')->name('customer.resend.otp');
});

// authenticated routes here
Route::group(['prefix' => 'customer', 'middleware' => ['auth:api', 'scopes:'.Roles::CUSOTMER.'']], function () {

    Route::get('profile', 'Customer\ProfileController@profile')->name('customer.profile');
    Route::post('profile-update', 'Customer\ProfileController@update')->name('customer.update.profile');
    Route::post('mobile-number-update', 'Customer\ProfileController@updatePhone')->name('customer.update.phone');
    Route::get('logout/{deviceId?}', 'Customer\LoginController@logout')->name('customer.logout');
    Route::get('delete', 'Customer\ProfileController@delete')->name('customer.delete');
    
    //Manage Address
    Route::apiResource('/address', 'Customer\AddressController');

    // Notifications
    Route::get('notifications', 'Push\UserNotificationController@notificationList')->name('customer.notification.list');
    Route::put('notification/read/{messageId}', 'Push\UserNotificationController@markNotificationRead')->name('customer.notification.read');
    Route::put('notification/read-display/{message_id}', 'Push\UserNotificationController@markNotificationReadDisplay')->name('customer.notification.display.read');
    Route::put('notification/display', 'Push\UserNotificationController@markNotificationsDiyplayed')->name('customer.notification.displayed');
    Route::delete('notification/delete/{messageId}', 'Push\UserNotificationController@deleteNotification')->name('customer.notification.delete');

});
