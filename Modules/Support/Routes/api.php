<?php

use Illuminate\Http\Request;



use Modules\Support\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

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

Route::middleware('auth:sanctum')->prefix('support')->group(function (Router $router) {
    $router->post('validate_code', [SupportController::class, 'validateCode'])->middleware(['abilities:support.read']);
    
});