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

// System Config globle
Route::get('/system/settings', 'App\Http\Controllers\Api\Home\SystemConfigController@index')->name('default.settings');
// Testing Job
Route::get('/test/job', 'App\Http\Controllers\Api\Home\SystemConfigController@testJob')->name('default.testJob');
// Clear
Route::get('/system/cache/{command}', 'App\Http\Controllers\Api\Home\SystemConfigController@command')->name('default.testJob');

// Testing Mail
Route::get('/test/mail', 'App\Http\Controllers\Api\Home\SystemConfigController@testMail')->name('default.testMail');

// Error reporting
Route::post('/error/report', 'App\Http\Controllers\Api\Home\SystemConfigController@ErrorReport')->name('default.errorReport');
