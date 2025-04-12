<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Settings\App\Http\Controllers\EmailController;
use Modules\Settings\App\Http\Controllers\ConfigurationController;

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

Route::middleware(['auth:sanctum'])->prefix('settings')->name('api.settings.')->group(function () {

    // Email Setting Routes
    Route::prefix('email')->name('email.')->group(function () {
        Route::get('/', [EmailController::class, 'index'])->name('index');
        Route::get('/{id}', [EmailController::class, 'show'])->name('show');
        Route::post('/store', [EmailController::class, 'store'])->name('store');
        Route::put('/{id}', [EmailController::class, 'update'])->name('update');
        Route::delete('/{id}', [EmailController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('configurations')->name('configurations.')->group(function () {
        Route::post('/', [ConfigurationController::class, 'index'])->name('index');
    });

    Route::prefix('holiday')->name('holiday.')->group(function () {
        Route::post('/add_holiday', [\Modules\Settings\App\Http\Controllers\HolidayController::class, 'store'])->name('store');
    });

});

//Route::middleware(['auth:sanctum'])->prefix('settings')->name('api.settings.')->group(function () {
//    Route::prefix('configurations')->name('email.')->group(function () {
//        Route::get('/', [ConfigurationController::class, 'index'])->name('index');
//    });
//});
