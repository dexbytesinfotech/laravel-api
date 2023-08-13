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

    Route::apiResource('/page', 'Pages\PageController', [
        'names' => [
            'index' => 'page.index',
            'store' => 'page.store',
            'update' => 'page.update',
            'destroy' => 'page.delete',
            'show' => 'page.show'
        ]
    ])->except(['edit', 'create']);
});

Route::get('/page/get-by-slug/{slug}', 'App\Http\Controllers\Api\Pages\PageController@getSlug')->name('page.get-by-slug');
