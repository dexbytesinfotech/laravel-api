<?php

use Illuminate\Support\Facades\Route;
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
Route::group(['prefix' => 'provider'], function() {
    Route::post('login', 'Provider\LoginController@login')->name('provider.login');
    Route::post('login-by-mobile', 'Provider\ForgotPasswordController@loginMobile')->name('provider.login.mobile');
    Route::post('signup-store', 'Provider\RegistrationController@signupStore')->name('provider.signup-store');
    Route::post('signup-manager', 'Provider\RegistrationController@signupManager')->name('provider.signup-manager');
    Route::post('signup', 'Provider\RegistrationController@signupProvider')->name('provider.signup');
    Route::post('forgot-password', 'Provider\ForgotPasswordController@forgotPasswordByMobile')->name('provider.forgot.password');
});

Route::group(['prefix' => 'provider', 'middleware' => ['auth:api', 'scopes:provider']], function () {
    Route::post('signup-verify-otp', 'Provider\RegistrationController@verifyOtp')->name('provider.signup.verify.otp');
    Route::get('resend-otp', 'Provider\LoginController@resendOtp')->name('provider.resend.otp');
    Route::get('profile', 'Provider\AccountController@profile')->name('provider.profile');
    Route::post('change-password', 'Provider\AccountController@changePassword')->name('provider.changepassword');
    Route::get('logout/{deviceId?}', 'Provider\LoginController@logout')->name('provider.logout');
    Route::post('profile-update', 'Provider\AccountController@update')->name('provider.update.profile');
    Route::post('mobile-number-update', 'Provider\AccountController@updatePhone')->name('provider.update.phone');
    Route::post('create-password', 'Provider\ForgotPasswordController@createPassword')->name('provider.createpassword');
    Route::post('verify-otp', 'Provider\ForgotPasswordController@verifyOtp')->name('provider.verify.otp');
    Route::post('is-open', 'Provider\AccountController@isOpen')->name('provider.is.open');

    Route::post('update-store', 'Provider\AccountController@updateStore')->name('provider.update.logo');
    Route::post('email-update', 'Provider\AccountController@emailUpdate')->name('provider.email.Update');
    Route::post('verify-email-otp', 'Provider\AccountController@verifyEmailOtp')->name('provider.verify.email.otp');

    //Notifications
    Route::get('notifications', 'Push\ProviderNotificationController@notificationList')->name('provider.notification.list');
    Route::put('notification/read/{messageId}', 'Push\ProviderNotificationController@markNotificationRead')->name('provider.notification.read');
    Route::put('notification/read-display/{message_id}', 'Push\ProviderNotificationController@markNotificationReadDisplay')->name('provider.notification.display.read');
    Route::put('notification/display', 'Push\ProviderNotificationController@markNotificationsDiyplayed')->name('provider.notification.displayed');
    Route::delete('notification/delete/{messageId}', 'Push\ProviderNotificationController@deleteNotification')->name('provider.notification.delete');
});
