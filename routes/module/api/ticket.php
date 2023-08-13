<?php
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

Route::group( ['middleware' => ['auth:api','scopes:admin'] ],function(){
    
    Route::apiResource('/ticket','App\Http\Controllers\Api\Tickets\TicketController'); 
    Route::apiResource('/tickets/ticket-category','App\Http\Controllers\Api\Tickets\TicketCategoryController'); 

});