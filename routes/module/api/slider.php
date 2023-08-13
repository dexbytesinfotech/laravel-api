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

    Route::apiResource('/slider/image', 'Slider\SliderImageController', [
        'names' => [
            'index' => 'slider.image.index',
            'store' => 'slider.image.store',
            'update' => 'slider.image.update',
            'destroy' => 'slider.image.delete',
            'show' => 'slider.image.show'
        ]
    ])->except(['edit', 'create']);
    Route::Post('/slider/image/bulk-delete', 'Slider\SliderImageController@bulkDelete')->name('slider.image.bulk-delete');
    Route::Get('/slider/image/group/{slider_groups_id}', 'Slider\SliderImageController@sliderImagesByGroupID')->name('slider.image.slider-image-by-group-id');
    Route::apiResource('/slider', 'Slider\SliderController', [
        'names' => [
            'index' => 'slider.index',
            'store' => 'slider.store',
            'update' => 'slider.update',
            'destroy' => 'slider.delete',
            'show' => 'slider.show'
        ]
    ])->except(['edit', 'create']);
});
