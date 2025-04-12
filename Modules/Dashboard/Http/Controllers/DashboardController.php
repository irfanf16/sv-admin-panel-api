<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Models\Invoices;
use App\Models\Settings\Email;
use App\Models\Subscriptions;
use App\Services\StripeService;
use http\Env\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\UserCompanies;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Jobs\CourseNotificationJob;
use App\Http\Requests\UsersStatusRequest;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('dashboard::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('dashboard::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('dashboard::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('dashboard::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /**
     * get company with total number of users.
     * @param int $id
     * @return Renderable
     */
    public function companies(Request $request)
    {
        $fields = !empty($request->fields) ? $request->fields : [];
        $sqlQuery = Company::companyWithTotalUsers($fields)->where('companies_users.profile_type', 'Owner');

        if($request->has("search") && !empty($request->search)){
            $sqlQuery->where('companies.title', 'LIKE', '%'.strtolower($request->search).'%');
        }

        if($request->has("limit"))
            $companies = $sqlQuery->paginate($request->limit?? config('settings.record_per_page'));

        $adv_ids = $sqlQuery->pluck('advocate_id');

        //if request has not got limit parameter then fetch all records
        if(!$request->has("limit"))
            $companies = $sqlQuery->get();

        if($adv_ids != null && $adv_ids->isNotEmpty()){
            $userIds = $adv_ids->toArray();
            //return array like id is index and name is index
            $adv_users = \DB::table('users')->select(['id', 'first_name','last_name'])->whereIn('id', array_filter($userIds))->get()->mapWithKeys(function ($user) {
                return [$user->id => $user->first_name . ' ' . $user->last_name];
            })->toArray();
            //inert company advocate username in company collection object
            if($companies != null && $companies->isNotEmpty()){
                foreach ($companies as $company){
                    if($company->advocate_id != null && isset($adv_users[$company->advocate_id]))
                        $company->SetAttribute('advocate', $adv_users[$company->advocate_id]);
                    else
                        $company->SetAttribute('advocate', null);
                }
            }


        }
        return $companies;
    }

    /**
     * get company with total number of users.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function companyWithUsers($id = 0, Request $request)
    {
        $subscription = null;
        $invoice_details = null;

        $company = Company::find($id);
        $subscription_id = null;
        if ($company->closure_plan == 0) {
            $subscription_id = $company->subscription_id;
        } else {
            $subscriptions = Subscriptions::where(['company_id' => $company->id])->latest()->first();
            if (!empty($subscriptions)) {
                $subscription_id = $subscriptions->subscription_id;
                $subscription = (new \App\Services\StripeService)->getSubscription($subscription_id);
                //get all subscription invoices
                $invoice_details = Invoices::where('subscription_id','=', $subscription_id)->get();
            }
        }
        $companyData = Company::CompanyWithUsers($id, $request->fields, $request->search, $request->status)->paginate($request->limit ?? config('settings.record_per_page'));

        if (empty($subscription_id)) {
            return response()->json(['companyData' => $companyData, 'companySubscription' => null, 'invoices' => null]);
        }


        return response()->json(['companyData' => $companyData, 'companySubscription' => $subscription, 'invoices' => $invoice_details]);
    }

    /**
     * get company with total number of users.
     * @param int $id
     * @return Renderable
     */
    public function toggleCompanyStatus($id = 0)
    {
        $company = Company::findOrFail($id);
        $company->status = (int)!$company->status;
        $company->update();
        return $company;
    }

    public function changeUserStatus($id = 0, UsersStatusRequest $request)
    {

        // $userCompany = new UserCompanies();
        // $userCompany->update_setting($request->user_id, ['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s')]);
        try {
            $userCompany = UserCompanies::findOrFail($request->user_id);
            $userCompany->status = $request->status;
            $userCompany->updated_at = date('Y-m-d H:i:s');
            $userCompany->update();

        } catch (\Throwable $th) {
            //throw $th;
        }
        return [
            'success' => true,
        ];
    }

    public function userInvite(Request $request)
    {

        if ($request->has(['tiny_url', 'userEmail', 'user_id', 'company_title'])) {
            $link = null;
            $userEmail = null;
            $email = null;
            /**
             * filled method is not fit for checking boolean request parameter because it always considers the false value as "empty", so boolean value parameters should check separately not within filled method
             */
            if ($request->filled(['tiny_url', 'userEmail', 'user_id', 'company_title'])) {
                $link = $request->tiny_url;
                $userEmail = $request->userEmail;
                $company_title = $request->company_title;
                //fetch email body based on these conditions from database
                $emailConditions = ['service' => "invite_user", "invite_user" => "invite_user", 'status' => 1, 'days' => '0'];
                $email = Email::where($emailConditions)->whereNull('deleted_at')->first();
                Log::debug("User_Invite_Email_RAW_QUERY: " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());

                if (optional($email)->email_body) {
                    /**
                     * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                     * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                     */
                    $search = ['[x_link]', '[company_name]'];
                    $replace = [$link, $company_title];
                    if (optional($email)->subject) {
                        $email->subject = str_replace(['[company_name]'], [$company_title], $email->subject);
                    }

                    (new \App\Services\StripeService)->sendMail($search, $replace, $request->user_id, $userEmail, $email);
                    return \response()->json('succeeded');
                } else {
                    Log::debug("User_Invite_Email_RESULT_NULL: ");
                }

            } else {
                Log::debug("User_Invite_Email_MISSING_PARAMS_VALUES: " . json_encode($request->all()));
            }

        } else {
            Log::debug("User_Invite_Email_MISSING_REQUIRED_PARAMS: " . json_encode($request->all()));
        }

    }

    public function userForgotPassword(Request $request)
    {

        if ($request->has(['tiny_url', 'userEmail', 'user_id'])) {
            $link = null;
            $userEmail = null;
            $email = null;
            /**
             * filled method is not fit for checking boolean request parameter because it always considers the false value as "empty", so boolean value parameters should check separately not within filled method
             */
            if ($request->filled(['tiny_url', 'userEmail', 'user_id'])) {
                $link = $request->tiny_url;
                $user_name = $request->user_name;
                $userEmail = $request->userEmail;
                //fetch email body based on these conditions from database
                $emailConditions = ['service' => "forgot_password", "forgot_password" => "forgot_password", 'status' => 1, 'days' => '0'];
                $email = Email::where($emailConditions)->whereNull('deleted_at')->first();
                Log::debug("FORGOT_PASSWORD_Email_RAW_QUERY: " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());

                if (optional($email)->email_body) {
                    /**
                     * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                     * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                     */
                    $search = ['[x_name]', '[x_link]'];
                    $replace = [$user_name, $link];
                    (new \App\Services\StripeService)->sendMail($search, $replace, $request->user_id, $userEmail, $email);
                    return \response()->json('succeeded');
                } else {
                    Log::debug("FORGOT_PASSWORD_Email_RESULT_NULL");
                }

            } else {
                Log::debug("FORGOT_PASSWORD_Email_MISSING_PARAMS_VALUES: " . json_encode($request->all()));
            }

        } else {
            Log::debug("FORGOT_PASSWORD_Email_MISSING_REQUIRED_PARAMS: " . json_encode($request->all()));
        }

    }
    public function save_job(Request $request) {
        $event_code = $request->event_code;
        $push_notification_ids = $request->push_notification_ids;
        $course = $request->course;
        $company_id = $request->company_id;
        $delaytime = (int) $request->delaytime;
        if(empty($delaytime)) {
            $delaytime = 60;
        };
        $delaytime = 0;
        dispatch(new CourseNotificationJob($event_code, $push_notification_ids, $course, $company_id))->delay(now()->addSeconds($delaytime));
        return response()->json(['status' => 'Job dispatched']);
    }
}
