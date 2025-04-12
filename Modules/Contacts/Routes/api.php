<?php

use Illuminate\Support\Facades\Route;
use Modules\Contacts\Http\Controllers\ContactsController;
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

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/contacts', [ContactsController::class, 'index'])->middleware(['abilities:contacts.read']);
    Route::post('/contacts', [ContactsController::class, 'store'])->name('store-contact')->middleware(['abilities:contacts.create']);
    Route::get('contacts/{contact}/edit', [ContactsController::class, 'edit'])->name('edit-contact')->middleware(['abilities:contacts.read']);
    Route::put('contacts/{contact}', [ContactsController::class, 'update'])->name('update-contact')->middleware(['abilities:contacts.update']);
    Route::delete('contacts/{contact}', [ContactsController::class, 'destroy'])->middleware(['abilities:contacts.delete']);
});
