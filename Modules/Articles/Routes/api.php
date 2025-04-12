<?php

use Illuminate\Http\Request;
use App\Http\Middleware\EnsureInternalCommunication;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Modules\Articles\Http\Controllers\ArticlesController;
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
Route::prefix('staffviz')->middleware([EnsureInternalCommunication::class])->group(function (Router $router) {
    $router->get('articles/{id?}', [ArticlesController::class, 'index'])->name('staffviz-artcles');
});