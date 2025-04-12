<?php

use Illuminate\Support\Facades\Route;
use App\Models\WebAppTrackingModel;
use Modules\Dashboard\Http\Controllers\CompanyController;
use App\Models\ModuleFeature;
use App\Services\StripeService;
use App\Models\AddonsHistory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// set all users password 12345678
// Route::get('update-user-password', [StaffvizRegisterController::class, 'updateUserPassword'])->name('update.user.password');
Route::get('addons-removal/{company_initial}/{date}', function($company_initial,$date){


    (new AddonsHistory())->addonsRemoval($company_initial, $date);

    dd('Addons removed successfully',$company_initial,$date);

})->name('addons.removal');

Route::get('/', function () {
    // dd(ModuleFeature::where('sub_module_id' ,  1)->with('featuresList')->get()->toArray());
    // $cm= new CompanyController();
    // dd($cm->deleteCompany());
    // return view('welcome');
    // dd( (new WebAppTrackingModel())->get_data() );
    // return (new WebAppTrackingModel())->get_data('ela_1005');
    // (new StripeService())->update_company_configuration(1098);
    // $da = (new StripeService())->get_configuration();
    // dd( $da, $da['trial_data_deletion_days'] );
});
