<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\Files\Http\Controllers\FilesAdminController;
use Modules\Files\Http\Controllers\FilesApiController;

/*
 * API routes
 */
Route::middleware(['auth:sanctum'])->group(function (Router $router) {
    $router->put('files/{file}', [FilesAdminController::class, 'update'])->name('update-file')->middleware(['abilities:files.update']);
    $router->get('files/{file}/edit', [FilesAdminController::class, 'edit'])->middleware(['abilities:files.read']);
    $router->get('files', [FilesApiController::class, 'index'])->middleware(['abilities:files.read']);
    $router->post('files', [FilesApiController::class, 'store'])->middleware(['abilities:files.create']);
    $router->patch('files/{ids}', [FilesApiController::class, 'move'])->middleware(['abilities:files.update']);
    $router->delete('files/{file}', [FilesApiController::class, 'destroy'])->middleware(['abilities:files.delete']);
});



