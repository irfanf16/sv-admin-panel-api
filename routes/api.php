<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\SettingsAdminController;
use App\Http\Controllers\PagesApiController;
use App\Http\Controllers\PageSectionsApiController;
use App\Http\Controllers\PagesAdminController;
use App\Http\Controllers\PageSectionsAdminController;
use App\Http\Controllers\RolesApiController;
use App\Http\Controllers\RolesAdminController;
use App\Http\Controllers\UsersApiController;
use App\Http\Controllers\UsersAdminController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\API\StaffvizRegisterController;
use App\Http\Controllers\API\ChatbotController;
use App\Http\Controllers\API\AddonsHistoryController;
use App\Http\Middleware\EnsureInternalCommunication;
use App\Http\Middleware\IsValidUser;
use Modules\Dashboard\Http\Controllers\CompanyController;
use Modules\Plans\Http\Controllers\CardController;
use Modules\Plans\Http\Controllers\PaymentMethodController;
use Modules\Plans\Http\Controllers\SubscriptionController;
use Modules\Plans\Http\Controllers\TalkToSalesController;
use Modules\Plans\Http\Controllers\InvoiceController;
use Modules\Plans\Http\Controllers\ProductController;
use Modules\Plans\Http\Controllers\UserSubscriptionDetailController;


// use App\Http\Controllers\FilesAdminController;
// use App\Http\Controllers\FilesApiController;

// Route::middleware('auth:sanctum')->group(function (Router $router) {
//     $router->put('files/{file}', [FilesAdminController::class, 'update'])->name('update-file')->middleware(['abilities:files.update']);
//     $router->get('files', [FilesApiController::class, 'index'])->middleware(['abilities:files.read']);
//     $router->post('files', [FilesApiController::class, 'store'])->middleware(['abilities:files.create']);
//     $router->patch('files/{ids}', [FilesApiController::class, 'move'])->middleware(['abilities:files.update']);
//     $router->delete('files/{file}', [FilesApiController::class, 'destroy'])->middleware(['abilities:files.delete']);
// });

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
Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('refresh', 'refresh');
    // Request new password
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    // Set new password
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset-action');
});


Route::prefix('staffviz')->middleware([EnsureInternalCommunication::class])->group(function (Router $router) {
    $router->post('login', [StaffvizRegisterController::class, 'login'])->name('staffviz-login');
    $router->post('users/company/list', [StaffvizRegisterController::class, 'get_user_company_list'])->name('users-company-lists');
    $router->post('user/{id}', [StaffvizRegisterController::class, 'userUpdate'])->name('staffviz-user-update')->middleware([IsValidUser::class]);
    $router->post('user', [StaffvizRegisterController::class, 'userAdd'])->name('staffviz-user-add');
    $router->get('varify/remember_token/{token}', [StaffvizRegisterController::class, 'rememberToken'])->name('staffviz-company-remember-token');
    $router->get('varify/ex-employee/{token}', [StaffvizRegisterController::class, 'get_detail_of_ex_employee'])->name('staffviz-company-ex-employee-verify');
    $router->post('add/remember_token', [CompanyController::class, 'resetPassword'])->name('staffviz-company-reset-password')->middleware([IsValidUser::class]);
    $router->post('company_user/{company_id}/{user_id}', [StaffvizRegisterController::class, 'companyUser'])->name('staffviz-company-user')->middleware([IsValidUser::class]);
    $router->post('company_user_terminate/{company_id}/{user_id}', [StaffvizRegisterController::class, 'terminate_active_company_users_bulk'])->name('staffviz-company-user-terminate')->middleware([IsValidUser::class]);
    $router->post('insert_user_master_and_company_db', [StaffvizRegisterController::class, 'insert_user_master_and_company_db'])->name('insert-user-master-and-company-db')->middleware([IsValidUser::class]);
    $router->post('company_user/terminate', [StaffvizRegisterController::class, 'terminate_company_users_bulk'])->name('staffviz-company-terminate')->middleware([IsValidUser::class]);
    $router->post('company/{id}', [CompanyController::class, 'update'])->name('staffviz-company-update')->middleware([IsValidUser::class]);
    $router->delete('company_user/{company_id}/{user_id}', [StaffvizRegisterController::class, 'deletecompanyUser'])->name('staffviz-user-delete-user-by-email')->middleware([IsValidUser::class]);
    $router->delete('company/{company_id}/{user_id}', [StaffvizRegisterController::class, 'deleteCompany'])->name('delete_company');
    $router->get('user/{email}', [StaffvizRegisterController::class, 'getUserByEmail'])->name('staffviz-user-get-user-by-email');
    $router->post('verify-user-with-company-exist', [StaffvizRegisterController::class, 'verifyUserWithCompanyExist'])->name('verify_user_with_company_exist');
    $router->post('authenticate-user', [StaffvizRegisterController::class, 'authenticateUser'])->name('authenticate_user');
    $router->post('add-company-web', [CompanyController::class, 'store'])->name('web_company_register');
    $router->post('company/invite_owner', [CompanyController::class, 'inviteOwner'])->name('web_invite_owner');

    $router->post('company_setup_mails', [CompanyController::class, 'companySetupTrialOrActiveEmail'])->name('companySetupTrialOrActiveEmail');
    $router->post('company_close_account', [CompanyController::class, 'companyCloseAccount'])->name('companyCloseAccount');



    // Cards api.
    $router->get('customer/{customer_id}/cards', [CardController::class, 'getCardsByCustomerId']);
    $router->get('customer/{customer_id}/cards/{card_id}', [CardController::class, 'getCardByCustomerIdCardId']);


    // Payment Methods api.
    $router->get('customer/{customer_id}/payment_methods', [PaymentMethodController::class, 'getPaymentMethodsByCustomerId']);
    $router->get('payment_methods/{payment_method_id}', [PaymentMethodController::class, 'getPaymentMethodByCustomerIdPmID']);
    $router->delete('payment_methods/{payment_method_id}', [PaymentMethodController::class, 'paymentMethodDetach']);

    // subscription
    $router->get('stripe/subscription/{id}', [SubscriptionController::class, 'show']);
    $router->post('stripe/subscription/{id}', [SubscriptionController::class, 'update']);
    $router->put('stripe/update/subscription_items/{id}', [SubscriptionController::class, 'updateSubscriptionItems']);
    $router->put('stripe/remove/subscription_items/{id}', [SubscriptionController::class, 'removeSubscriptionItems']);
    $router->delete('stripe/subscription/{id}', [SubscriptionController::class, 'destroy']);

    $router->get('company/{id}/closure_plan/add', [CompanyController::class, 'closure_plan_add'])->name('closure_plan_add');
    $router->get('company/{id}/closure_plan/remove', [CompanyController::class, 'closure_plan_remove'])->name('closure_plan_remove');

    $router->post('stripe/subscription/{id}/quantity', [SubscriptionController::class, 'updateSubscriptionQuantity']);

    // talk to sales
    $router->post('talk_to_sales', [TalkToSalesController::class, 'store']);
    $router->post('user_subscription_info',[UserSubscriptionDetailController::class, 'store'])->name('user_subscription_info');


    // addons history removed
    $router->post('addons-history-remove', [SubscriptionController::class, 'addonsHistoryRemove']);


    // Invoices
    $router->post('invoices', [InvoiceController::class, 'index']);
    $router->put('invoice/{id}', [InvoiceController::class, 'update']);
    $router->post('invoice/upcoming/{customer_id}', [InvoiceController::class, 'upcoming']);
    $router->post('invoice/{id}/pay', [InvoiceController::class, 'pay']);


    $router->get('company/{company_id}/owner', [CompanyController::class, 'companyOwner']);
    $router->get('company/{company_id}/instance', [CompanyController::class, 'companyInstance']);



    $router->post('childcompany/store', [CompanyController::class, 'createChildCompany']);

    $router->get('product/{company_id}/current_package', [ProductController::class, 'currentPackage']);

    $router->post('userInvite', [\Modules\Dashboard\Http\Controllers\DashboardController::class, 'userInvite']);
    $router->post('userForgotPassword', [\Modules\Dashboard\Http\Controllers\DashboardController::class, 'userForgotPassword']);
    // Course Module Jobs
    $router->post('/course-job-dispatch', [ \Modules\Dashboard\Http\Controllers\DashboardController::class, 'save_job']);

    // Affiliate
    $router->post('affiliate/save', [\Modules\Affiliate\App\Http\Controllers\AffiliateController::class, 'store'])->name('affiliate-store');
    $router->get('affiliate/{email}', [\Modules\Affiliate\App\Http\Controllers\AffiliateController::class, 'show'])->name('affiliate-show');

    // Chat Bot Routes
    $router->post('/save_guest_client', [ ChatbotController::class, 'save_guest_client']);
    $router->post('/save_guest_client_rating', [ ChatbotController::class, 'save_guest_client_rating']);

    // Addons History Table
    $router->post('stripe/addons-history/upsert', [AddonsHistoryController::class, 'upsert']);


});

Route::middleware('auth:sanctum')->group( function () {
    //Company Close Accounts which needed for admin side and worked under authentication using sanctum
    Route::post('close_accounts', [CompanyController::class, 'fetchCompanyCloseAccount'])->name('fetchCompanyCloseAccount');

    Route::get('/logout', [RegisterController::class, 'logout']);
});


// Setting Routes
Route::middleware(['auth:sanctum'])->group(function (Router $router) {
    $router->get('settings', [SettingsAdminController::class, 'index'])->name('index-settings')->middleware(['abilities:settings.read']);
    $router->post('settings', [SettingsAdminController::class, 'save'])->name('update-settings')->middleware(['abilities:settings.update']);
    $router->get('cache/clear', [SettingsAdminController::class, 'clearCache'])->name('clear-cache')->middleware(['abilities:settings.update']);
    $router->patch('settings', [SettingsAdminController::class, 'deleteImage'])->name('delete-image-in-settings')->middleware(['abilities:settings.delete']);

    $router->get('talk_to_sales', [TalkToSalesController::class, 'index']);
});
Route::get('test', [TalkToSalesController::class, 'testr']);


// authenticating talk to sales Routes
Route::middleware(['auth:sanctum'])->group(function (Router $router) {
    $router->get('talk_to_sales', [TalkToSalesController::class, 'index'])->middleware(['abilities:talk_to_sales.read']);
});


/*
 * Pages routes
 */
Route::middleware(['auth:sanctum'])->group(function (Router $router) {
    $router->get('pages', [PagesApiController::class, 'index'])->middleware(['abilities:pages.read']);
    $router->get('pages/links-for-editor', [PagesApiController::class, 'linksForEditor'])->middleware(['abilities:pages.update']);
    $router->patch('pages/{page}', [PagesApiController::class, 'updatePartial'])->middleware(['abilities:pages.update']);
    $router->post('pages/sort', [PagesApiController::class, 'sort'])->middleware(['abilities:pages.read']);
    $router->delete('pages/{page}', [PagesApiController::class, 'destroy'])->middleware(['abilities:pages.delete']);
    $router->get('pages/{page}/sections', [PageSectionsApiController::class, 'index'])->middleware(['abilities:page_sections.read']);
    $router->patch('pages/{page}/sections/{section}', [PageSectionsApiController::class, 'updatePartial'])->middleware(['abilities:page_sections.update']);
    $router->delete('pages/{page}/sections/{section}', [PageSectionsApiController::class, 'destroy'])->middleware(['abilities:page_sections.delete']);

    // Custom pages
    $router->post('pages/server_side_exceptions', [PagesApiController::class, 'serverSideExceptions'])->middleware(['abilities:pages.read']);
});

/*
 * Admin routes
 */
Route::middleware('auth:sanctum')->group(function (Router $router) {
    $router->get('pages/create', [PagesAdminController::class, 'create'])->name('create-page')->middleware(['abilities:pages.create']);
    $router->get('pages/{page}/edit', [PagesAdminController::class, 'edit'])->name('edit-page')->middleware(['abilities:pages.update']);
    $router->post('pages', [PagesAdminController::class, 'store'])->name('store-page')->middleware(['abilities:pages.create']);
    $router->put('pages/{page}', [PagesAdminController::class, 'update'])->name('update-page')->middleware(['abilities:pages.update']);

    $router->get('pages/{page}/sections/create', [PageSectionsAdminController::class, 'create'])->name('create-page_section')->middleware(['abilities:page_sections.create']);
    $router->get('pages/{page}/sections/{section}/edit', [PageSectionsAdminController::class, 'edit'])->name('edit-page_section')->middleware(['abilities:page_sections.read']);
    $router->post('pages/{page}/sections', [PageSectionsAdminController::class, 'store'])->name('store-page_section')->middleware(['abilities:page_sections.create']);
    $router->put('pages/{page}/sections/{section}', [PageSectionsAdminController::class, 'update'])->name('update-page_section')->middleware(['abilities:page_sections.update']);
    $router->post('pages/{page}/sections/sort', [PageSectionsAdminController::class, 'sort'])->name('sort-page_sections')->middleware(['abilities:page_sections.read']);;

    $router->get('sections', [PageSectionsAdminController::class, 'index'])->name('index-page_sections')->middleware(['abilities:page_sections.read']);
    $router->delete('sections/{section}', [PageSectionsAdminController::class, 'destroyMultiple'])->name('destroy-page_section')->middleware(['abilities:page_sections.read']);

    // $router->get('{uri}', [PagesAdminController::class, 'notFound'])->name('show-404-page-in-admin')->where('uri', '(.*)');
});


/**
 * Roles
 */
/*
 * API routes
 */
Route::middleware('auth:sanctum')->group(function (Router $router) {
    $router->get('roles', [RolesApiController::class, 'index'])->middleware(['abilities:permissions.read']);
    $router->patch('roles/{role}', [RolesApiController::class, 'updatePartial'])->middleware(['abilities:permissions.update']);
    $router->delete('roles/{role}', [RolesApiController::class, 'destroy'])->middleware(['abilities:permissions.delete']);
    $router->get('roles/{role}/view', [RolesAdminController::class, 'edit'])->name('edit-role')->middleware(['abilities:permissions.read']);
    $router->put('roles/{role}', [RolesAdminController::class, 'update'])->name('update-role')->middleware(['abilities:permissions.update']);
    $router->post('roles', [RolesAdminController::class, 'store'])->name('store-role')->middleware(['abilities:permissions.create']);
    $router->post('roles_list', [RolesAdminController::class, 'usersList'])->name('role_list')->middleware(['abilities:permissions.read']);
    $router->post('advocate_roles', [RolesAdminController::class, 'rolesList'])->name('adv_roles')->middleware(['abilities:permissions.read']);
    $router->post('updateStatus', [UsersAdminController::class, 'updateStatus'])->name('updateStatus')->middleware(['abilities:permissions.update']);

});


/**
 * User Apis
 */
 Route::middleware('auth:sanctum')->group(function (Router $router) {
    $router->get('users', [UsersAdminController::class, 'index'])->name('index-users')->middleware(['abilities:users.read']);
    $router->get('users/export', [UsersAdminController::class, 'export'])->name('export-users')->middleware(['abilities:users.read']);
    $router->get('users/create', [UsersAdminController::class, 'create'])->name('create-user')->middleware(['abilities:users.create']);
    $router->get('users/{user}/edit', [UsersAdminController::class, 'edit'])->name('edit-user')->middleware(['abilities:users.read']);
    $router->post('users', [UsersAdminController::class, 'store'])->name('store-user')->middleware(['abilities:users.create']);
    $router->get('users/{id}', [RolesAdminController::class, 'user'])->name('read-user')->middleware(['abilities:users.read']);
    $router->put('users/{user}', [UsersAdminController::class, 'update'])->name('update-user')->middleware(['abilities:users.update']);
    $router->delete('deleteUsers/{user}', [UsersAdminController::class, 'destroy'])->name('delete-user')->middleware(['abilities:users.delete']);
    // $router->get('users/{id}/impersonate', [ImpersonateController::class, 'start'])->name('impersonate-user')->middleware(['abilities:impersonate.users']);
});

Route::middleware('auth:sanctum')->group(function (Router $router) {
    $router->get('users', [UsersApiController::class, 'index'])->middleware(['abilities:users.read']);
    $router->post('users/current/updatepreferences', [UsersApiController::class, 'updatePreferences'])->middleware(['abilities:update.read']);
    $router->delete('users/{user}', [UsersApiController::class, 'destroy'])->middleware(['abilities:users.delete']);
});
