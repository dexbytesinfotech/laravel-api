<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'notification', 'middleware' => ['auth:api', 'scopes:admin']], function () {

    Route::post('/send-all', 'App\Http\Controllers\Api\Push\NotificationController@sendToAllNotification')->name('send.notifications');
    Route::post('/add-notification', 'App\Http\Controllers\Api\Push\NotificationController@addNotificationMessage')->name('add.notification');
    Route::post('/send-notification', 'App\Http\Controllers\Api\Push\NotificationController@sendScheduledPushNotification')->name('send.notification');
});
