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

Route::group(['middleware' => ['auth:api', 'scopes:admin']], function () {

    Route::apiResource('/user', 'Users\UserController');
    Route::Post('/user/bulk-delete', 'Users\UserController@bulkDelete');

    Route::apiResource('/role', 'Roles\RoleController');
    Route::apiResource('/roles/permission', 'Roles\PermissionController');
});
