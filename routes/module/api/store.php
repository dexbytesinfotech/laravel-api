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

    //Store Types 
    Route::apiResource('/store-type', 'App\Http\Controllers\Api\Stores\StoreTypeController', [
        'names' => [
            'index' => 'store.store_types.index',
            'store' => 'store.store_types.store',
            'update' => 'store.store_types.update',
            'destroy' => 'store.store_types.delete',
            'show' => 'store.store_types.show'
        ]
    ])->except(['edit', 'create']);

   
    //Manage Store
    Route::apiResource('/store', 'App\Http\Controllers\Api\Stores\StoreController', [
        'names' => [
            'index' => 'store.index',
            'store' => 'store.store',
            'update' => 'store.update',
            'destroy' => 'store.delete',
            'show'  => 'store.show'
        ]
    ])->except(['edit', 'create']);


    Route::post('/store/bulk-delete', 'App\Http\Controllers\Api\Stores\StoreController@bulkDelete')->name('store.delete.bulk');

    //Manage store providers 
    Route::post('/store/get-all-providers', 'App\Http\Controllers\Api\Stores\ProviderController@getAllProvider')->name('store.users.provider');
    Route::post('/store/add-provider', 'App\Http\Controllers\Api\Stores\ProviderController@storeProvider')->name('store.provider.add');
    Route::post('/store/remove-provider', 'App\Http\Controllers\Api\Stores\ProviderController@removeProvider')->name('store.provider.remove');

    // Manage store business hours 
    Route::post('/store/business-hours', 'App\Http\Controllers\Api\Stores\BusinessHoursController@getBusinessHours')->name('store.business_hours.get');
    Route::post('/store/update-business-hours', 'App\Http\Controllers\Api\Stores\BusinessHoursController@updateBusinessHours')->name('store.business_hours.update');
});

Route::get('/active-store-types', 'App\Http\Controllers\Api\Stores\StoreTypeController@ActiveStoreType')->name('store.store_types.get');



