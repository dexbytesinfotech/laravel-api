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

Route::group(['prefix' => 'admin'], function () {
    Route::post('login', 'Admin\LoginController@login')->name('admin.login');
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'scopes:admin']], function () {
    // authenticated routes here 
    Route::get('profile', 'Admin\AccountController@profile')->name('admin.profile');
    Route::post('change-password', 'Admin\AccountController@changePassword')->name('admin.changepassword');
    Route::get('logout', 'Admin\LoginController@logout')->name('admin.logout');
});
