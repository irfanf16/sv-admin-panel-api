<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UsersAdminController;
use App\Http\Controllers\UsersApiController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\JavaScriptData;

/*
 * Front office routes
 */
foreach (locales() as $lang) {
    Route::middleware(['public', JavaScriptData::class])->prefix($lang)->name($lang.'::')->group(function (Router $router) {
        if (config('typicms.register')) {
            // Registration
            $router->get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
            $router->post('register', [RegisterController::class, 'register'])->name('register-action');
            // Verify
            $router->get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
            $router->get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
            $router->get('email/verified', [VerificationController::class, 'verified'])->name('verification.verified');
            $router->get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
        }
        // Login
        $router->get('login', [LoginController::class, 'showLoginForm'])->name('login');
        $router->post('login', [LoginController::class, 'login'])->name('login-action');
        // Request new password
        $router->get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        $router->post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        // Set new password
        $router->get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        $router->post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset-action');
        // Logout
        $router->post('logout', [LoginController::class, 'logout'])->name('logout');
        // Impersonate
        $router->get('stop-impersonation', [ImpersonateController::class, 'stopImpersonation'])->name('stop-impersonation');
    });
}

Route::redirect('/.well-known/change-password', '/'.app()->getLocale().'/password/reset');

/*
 * Admin routes
 */
Route::middleware('admin')->prefix('admin')->name('admin::')->group(function (Router $router) {
    $router->get('users', [UsersAdminController::class, 'index'])->name('index-users')->middleware('can:read users');
    $router->get('users/export', [UsersAdminController::class, 'export'])->name('export-users')->middleware('can:read users');
    $router->get('users/create', [UsersAdminController::class, 'create'])->name('create-user')->middleware('can:create users');
    $router->get('users/{user}/edit', [UsersAdminController::class, 'edit'])->name('edit-user')->middleware('can:read users');
    $router->post('users', [UsersAdminController::class, 'store'])->name('store-user')->middleware('can:create users');
    $router->put('users/{user}', [UsersAdminController::class, 'update'])->name('update-user')->middleware('can:update users');
    $router->get('users/{id}/impersonate', [ImpersonateController::class, 'start'])->name('impersonate-user')->middleware('can:impersonate users');
});

/*
 * API routes
 */
Route::middleware(['api', 'auth:api'])->prefix('api')->group(function (Router $router) {
    $router->get('users', [UsersApiController::class, 'index'])->middleware('can:read users');
    $router->post('users/current/updatepreferences', [UsersApiController::class, 'updatePreferences'])->middleware('can:update users');
    $router->delete('users/{user}', [UsersApiController::class, 'destroy'])->middleware('can:delete users');
});
