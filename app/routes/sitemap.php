<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitemapPublicController;

/*
 * Front office routes
 */
Route::get('sitemap.xml', [SitemapPublicController::class, 'generate'])->name('sitemap');
