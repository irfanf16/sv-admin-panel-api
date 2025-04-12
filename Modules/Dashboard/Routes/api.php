<?php
use Modules\Dashboard\Http\Controllers\DashboardController;
use Modules\Dashboard\Http\Controllers\CompanyController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Middleware\EnsureInternalCommunication;

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

// Route::middleware('auth:api')->get('/dashboard', function (Request $request) {
//     return $request->user();
// });

Route::middleware([EnsureInternalCommunication::class])->group(function (Router $router) {
    $router->post('company/update/{id}', [CompanyController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function (Router $router) {
    $router->post('company/store', [CompanyController::class, 'store'])->middleware(['abilities:company_management.create']);
    $router->post('company/invite_owner', [CompanyController::class, 'inviteOwner'])->middleware(['abilities:company_management.create']);
    $router->get('deleteCompany/{company_id}', [CompanyController::class, 'deleteCompany'])->middleware(['abilities:company_management.read']);


    $router->post('companies', [DashboardController::class, 'companies'])->middleware(['abilities:company_management.read']);
    $router->post('company/{company_id}/change_status', [DashboardController::class, 'toggleCompanyStatus'])->middleware(['abilities:company_management.update']);
    $router->post('company/company_update', [CompanyController::class, 'updateCompany'])->middleware(['abilities:company_management.update']);
    $router->post('company/email', [CompanyController::class, 'email']);
    $router->post('company/{company_id}/users', [DashboardController::class, 'companyWithUsers'])->middleware(['abilities:users.read']);
    $router->post('company/{company_id}/users/change_status', [DashboardController::class, 'changeUserStatus'])->middleware(['abilities:users.update']);



    $router->post('company/search/subscriptions', [CompanyController::class, 'searchSubscriptionProduct']);
    // $router->patch('roles/{role}', [RolesApiController::class, 'updatePartial'])->middleware(['abilities:roles.update']);
    // $router->delete('roles/{role}', [RolesApiController::class, 'destroy'])->middleware(['abilities:roles.delete']);
    // $router->get('roles/{role}/view', [RolesAdminController::class, 'edit'])->name('edit-role')->middleware(['abilities:roles.read']);
    // $router->put('roles/{role}', [RolesAdminController::class, 'update'])->name('update-role')->middleware(['abilities:roles.update']);
    // $router->post('roles', [RolesAdminController::class, 'store'])->name('store-role')->middleware(['abilities:roles.create']);
});
