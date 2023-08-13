<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Settings\TranslationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function()
{
    /**
     * Auth Routes
     */
    Auth::routes();

    /**
     * Home Routes
    */
    Route::get('/', 'WelcomeController@index');
    Route::get('lang/{locale}', 'Settings\LocalizationController@index')->name('localization.change');
 
    //Translation settings
    Route::get('/translation/create', [TranslationController::class, 'create'])->name('language.create'); 
    Route::post('/translation/store', [TranslationController::class, 'store'])->name('language.store');   
    Route::get('/translation', [TranslationController::class, 'index'])->name('translation.index');   
     Route::get('/translation/edit/{lang}/{dir?}/{file?}', [TranslationController::class, 'edit'])->name('translation.edit');
    Route::post('/translation/save', [TranslationController::class, 'save'])->name('translation.save');
    Route::post('/translation/translate', [TranslationController::class, 'translate'])->name('translation.translate');  
   
});
