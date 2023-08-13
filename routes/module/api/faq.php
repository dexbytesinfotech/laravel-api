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

    Route::apiResource('/faq/category', 'Faq\CategoryController');
    Route::apiResource('/faq', 'Faq\FaqController');
    Route::post('/faq/bulk-delete', 'App\Http\Controllers\Api\Faq\FaqController@bulkDelete');
});

Route::get('/faq/user/{roleType?}', 'App\Http\Controllers\Api\Faq\FaqController@activeFaq');
