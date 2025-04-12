<?php

use App\Http\Middleware\AbilityMiddleware;
use Illuminate\Http\Request;
use Modules\Plans\Http\Controllers\PlanModuleController;
use Modules\Plans\Http\Controllers\PFeaturesController;
use Modules\Plans\Http\Controllers\PFeaturesListController;
use Modules\Plans\Http\Controllers\CouponController;
use Modules\Plans\Http\Controllers\ProductController;
use Modules\Plans\Http\Controllers\CategoryController;
use Modules\Plans\Http\Controllers\CategoryFeaturesController;
use Modules\Plans\Http\Controllers\InvoiceController;
use Modules\Plans\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Middleware\EnsureInternalCommunication;
use Modules\Plans\Http\Controllers\PaymentMethodController;
use Modules\Plans\Http\Controllers\PlansController;
use Modules\Plans\Http\Controllers\SubscriptionController;
use Modules\Plans\Http\Controllers\StripWebhookController;
use Modules\Affiliate\App\Http\Controllers\TrackdeskWebhookController;

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

Route::middleware('auth:sanctum')->group(function (Router $router) {
    $router->get('modules/{parent_id?}', [PlanModuleController::class, 'getModules'])->middleware(['abilities:plan_packages.read']);
    $router->get('modules/features/{sub_module_id}', [PFeaturesController::class, 'getModulesFeaturesByModuleId'])->middleware(['abilities:plan_packages.read']);
    // Features
    $router->get('features', [PFeaturesController::class, 'index'])->middleware(['abilities:features.read']);
    $router->get('features/{id}', [PFeaturesController::class, 'show'])->middleware(['abilities:features.read']);
    $router->post('features', [PFeaturesController::class, 'store'])->middleware(['abilities:features.create']);
    $router->put('features/{id}', [PFeaturesController::class, 'update'])->middleware(['abilities:features.update']);
    $router->delete('features/{id}', [PFeaturesController::class, 'destroy'])->middleware(['abilities:features.delete']);
    // Features List
    $router->get('features_list/{feature_id?}', [PFeaturesListController::class, 'featuresListByFeatureId'])->middleware(['abilities:features.read']);


    // coupons
    $router->get('coupons', [CouponController::class, 'index'])->middleware(['abilities:coupons.read']);
    $router->get('coupons/{id}', [CouponController::class, 'show'])->middleware(['abilities:coupons.read']);
    $router->delete('coupons/{id}', [CouponController::class, 'destroy'])->middleware(['abilities:coupons.delete']);
    $router->post('coupons', [CouponController::class, 'store'])->middleware(['abilities:coupons.create']);
    $router->put('coupons/{id}', [CouponController::class, 'update'])->middleware(['abilities:coupons.update']);



    $router->get('addons', [ProductController::class, 'index'])->middleware(['abilities:addons.read']);
    $router->get('addons/{id}', [ProductController::class, 'show'])->middleware(['abilities:addons.read']);
    $router->delete('addons/{id}', [ProductController::class, 'destroy'])->middleware(['abilities:addons.delete']);
    $router->post('addons', [ProductController::class, 'store'])->middleware(['abilities:addons.create']);
//    $router->put('update_product_status', [ProductController::class, 'addons']);//->middleware(['abilities:addons.update']);
//    $router->put('update_plan_sorting', [ProductController::class, 'updatePlansSorting']);//->middleware(['abilities:plansandpackages.update']);
    $router->put('addons/{id}', [ProductController::class, 'update'])->middleware(['abilities:addons.update']);
    $router->post('local/products', [ProductController::class, 'productsLocal'])->middleware(['abilities:addons.read']);
    $router->get('local/product/{id}', [ProductController::class, 'productLocal'])->middleware(['abilities:addons.read']);

    $router->get('plans', [ProductController::class, 'index'])->middleware(['abilities:plans.read']);
    $router->get('plans/{id}', [ProductController::class, 'show'])->middleware(['abilities:plans.read']);
    $router->delete('plans/{id}', [ProductController::class, 'destroy'])->middleware(['abilities:plans.delete']);
    $router->post('plans', [ProductController::class, 'store'])->middleware(['abilities:plans.create']);
    $router->put('plans_update_product_status', [ProductController::class, 'updateProductPublicStatus'])->middleware(['abilities:plans.update']);
    $router->put('plans_update_plan_sorting', [ProductController::class, 'updatePlansSorting'])->middleware(['abilities:plans.update']);
    $router->put('plans/{id}', [ProductController::class, 'update'])->middleware(['abilities:plans.update']);
    $router->post('local/plans', [ProductController::class, 'productsLocal'])->middleware(['abilities:plans.read']);
    $router->get('local/plan/{id}', [ProductController::class, 'productLocal']);


    // categories
    $router->post('categories', [CategoryController::class, 'index'])->middleware(['abilities:products.read']);
    $router->get('category/{id}', [CategoryController::class, 'show'])->middleware(['abilities:products.read']);
    $router->delete('category/{id}', [CategoryController::class, 'destroy'])->middleware(['abilities:products.delete']);
    $router->post('category', [CategoryController::class, 'store'])->middleware(['abilities:products.create']);
    $router->put('category/{id}', [CategoryController::class, 'update'])->middleware(['abilities:products.update']);

    // category Features
    $router->get('category/{id}/features', [CategoryFeaturesController::class, 'show'])->middleware(['abilities:products.read']);
    $router->delete('category/{id}/features/{feature_id}', [CategoryFeaturesController::class, 'destroy'])->middleware(['abilities:products.delete']);
    $router->delete('category/{id}/features_list/{feature_list_id}', [CategoryFeaturesController::class, 'destroyFeatureList'])->middleware(['abilities:products.delete']);
    $router->post('category/{id}/features', [CategoryFeaturesController::class, 'store'])->middleware(['abilities:products.create']);


    // invoices
    $router->post('invoices', [InvoiceController::class, 'index'])->middleware(['abilities:plansandpackages.read']);
    $router->put('invoice/{id}', [InvoiceController::class, 'update'])->middleware(['abilities:plansandpackages.read']);
    $router->post('invoice/upcoming', [InvoiceController::class, 'upcoming'])->middleware(['abilities:plansandpackages.read']);
    $router->post('invoice/{id}/pay', [InvoiceController::class, 'pay'])->middleware(['abilities:plansandpackages.read']);


    // Prices
    $router->get('prices', [ProductController::class, 'prices'])->middleware(['abilities:plansandpackages.read']);
    $router->delete('prices/{id}/{stripe_id?}', [ProductController::class, 'pricesDestroy'])->middleware(['abilities:plansandpackages.delete']);


    // Create Customer
    // Route::group(['prefix'=>'customers','as'=>'customers.'], function(Router $router){
    //     $router->get('/', [CustomerController::class, 'index'])->middleware(['abilities:plansandpackages.read']);
    //     $router->post('/search', [CustomerController::class, 'search'])->middleware(['abilities:plansandpackages.read']);
    //     $router->get('/{id}', [CustomerController::class, 'show'])->middleware(['abilities:plansandpackages.read']);
    //     $router->delete('/{id}', [CustomerController::class, 'destroy'])->middleware(['abilities:plansandpackages.delete']);
    //     $router->post('/', [CustomerController::class, 'store'])->middleware(['abilities:plansandpackages.create']);
    //     $router->put('/{id}', [CustomerController::class, 'update'])->middleware(['abilities:plansandpackages.update']);
    // });

    $router->post('user_subscription_info', [\Modules\Plans\Http\Controllers\UserSubscriptionDetailController::class, 'store']);
});

// Customers . These routes will execute from the app.staffviz.com or staffvizz.com
Route::middleware([EnsureInternalCommunication::class])->prefix('customers')->group(function (Router $router) {
    $router->get('/', [CustomerController::class, 'index']);
    $router->post('/search', [CustomerController::class, 'search']);
    $router->get('/{id}', [CustomerController::class, 'show']);
    $router->delete('/{id}', [CustomerController::class, 'destroy']);
    $router->post('/', [CustomerController::class, 'store']);
    $router->put('/{id}', [CustomerController::class, 'update']);


});
// Front-end apis
Route::middleware([EnsureInternalCommunication::class])->group(function (Router $router) {
    $router->post('local/internal-user-products', [ProductController::class, 'productsLocal']);
    $router->get('local/internal-user-product/{id}', [ProductController::class, 'productLocal']);
    $router->post('local/internal-user-categories', [CategoryController::class, 'index']);
    $router->get('local/internal-user-category/{id}', [CategoryController::class, 'show']);
    $router->get('local/internal-user-features', [PFeaturesController::class, 'index']);
    $router->get('local/category-features', [CategoryFeaturesController::class, 'list']);
});

// checkout
Route::middleware([EnsureInternalCommunication::class])->group(function (Router $router) {
    $router->post('stripe/setupintent', [PlansController::class, 'setupintent']);
    $router->post('stripe/setupintent/confirm', [PlansController::class, 'setupintentconfirm']);
});

// subscription
Route::middleware([EnsureInternalCommunication::class])->group(function (Router $router) {
    $router->post('stripe/subscription/create', [SubscriptionController::class, 'store']);
    $router->get('stripe/subscription/{id}', [SubscriptionController::class, 'show']);
    $router->delete('stripe/subscription/{id}', [SubscriptionController::class, 'destroy']);
    $router->get('apply/coupon/{id}', [SubscriptionController::class, 'coupon']);

});


// webhook
Route::post('stripe/webhook', [StripWebhookController::class, 'index']);
Route::get('stripe/webhook', [StripWebhookController::class, 'alive']);
Route::post('trackdesk/webhook', [TrackdeskWebhookController::class, 'index']);
Route::get('trackdesk/webhook', [TrackdeskWebhookController::class, 'alive']);

// Jobs test Url
$router->get('jobs/start_trial', [SubscriptionController::class, 'start_trial']);
$router->get('jobs/grace_period_run', [SubscriptionController::class, 'grace_period_run']);
