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

    Route::get('/worlds/country/getStateByCountry/{country_id}', 'Worlds\CountryController@state');
    Route::get('/worlds/state/getCitiByState/{state_id}', 'Worlds\StateController@citi');
    Route::get('/worlds/country/getCityByCountry/{country_id}', 'Worlds\CountryController@citi');
    Route::apiResource('/worlds/country', 'Worlds\CountryController');
    Route::apiResource('/worlds/state', 'Worlds\StateController');
    Route::apiResource('/worlds/citi', 'Worlds\CitiController');
    
});
Route::get('worlds/mobile/country','Worlds\MobileController@index');
    Route::get('worlds/mobile/states/{country_id}','Worlds\MobileController@state');
    Route::post('worlds/mobile/cities','Worlds\MobileController@city');
