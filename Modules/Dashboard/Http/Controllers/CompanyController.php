<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Models\CompanyCloseAccount;
use App\Models\Settings\Email;
use App\Models\Subscriptions;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Company;
use App\Models\UserTableMaster;
use App\Models\UserCompanies;
use App\Http\Requests\UsersStatusRequest;
use App\Models\Configuration;
use App\Models\CompaniesConfiguration;

use App\Models\Instance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Modules\Dashboard\App\Http\Requests\checkReqBeforeCompanyDelete;
use Modules\Dashboard\App\Http\Requests\CompanyCloseAccountPostRequest;
use Modules\Dashboard\App\Http\Requests\UpdateCompanyPostRequest;
use Modules\Dashboard\Http\Requests\CompanyRequest;
use Modules\Dashboard\Http\Requests\CreateChildCompanyRequest;
use Illuminate\Support\Facades\DB;
use App\Services\createNewDatabaseService;
use App\Mail\AdminNotificationForCompanyCreate;
use Illuminate\Support\Facades\Mail;
use Modules\Dashboard\Http\Requests\InviteOwnerRequest;
use App\Services\EncryptionDecryption;
use App\Libraries\Generic;
use App\Libraries\Masterdb;
use App\Services\StripeService;
use Stripe\Subscription;
use Illuminate\Support\Facades\Http;
class CompanyController extends Controller
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
    public function store(CompanyRequest $request, createNewDatabaseService $createNewDatabase)
    {

        try {
            DB::beginTransaction();
            $collations = DB::select("SHOW COLLATION");
            $db_charset = 'utf8';
            $db_collation = 'utf8_unicode_ci';
            $key = array_search('utf8mb4_0900_ai_ci', array_column($collations, 'Collation'));
            if ($key == false) {
                if (str_contains($request->header('User-Agent'), 'Postman')) {

                } else {
                    return response()->json([
                        "message" => 'Database collation : utf8mb4_0900_ai_ci not found. Please contact to server admin.',
                        "errors" => [
                            "error" => [
                                'Database collation : utf8mb4_0900_ai_ci not found. Please contact to server admin.'
                            ]
                        ]
                    ], 500);
                }

            } else {
                $db_charset = $collations[$key]->Charset;
                $db_collation = $collations[$key]->Collation;
            }
            $instance = Instance::decrypt()->latest()->first();
            $instance_id = $instance ? $instance->id : 1;
            // Register User here...
            $userMaster = false;

            $user = UserTableMaster::where('email', $request['owner_email'])->first();
            if (empty($user)) {
                $name = explode(" ", $request->owner_name);
                $first_name = array_shift($name);
                $last_name = implode(" ", $name);
                $stripeService = new StripeService();
                $stripeCustomer = $stripeService->upsertCustomer($request['owner_email'], [
                    'name' => $request->owner_name,
                    'email' => $request['owner_email'],
                ]);
                $data = [
                    'email' => $request['owner_email'],
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'remember_token' => Str::random(100),
                    "stripe_customer_id" => $stripeCustomer->id
                    // 'password' => Hash::make('Cre@12345')
                ];
                $user = UserTableMaster::create($data);
                $userMaster = true;
            }

            $super_admin_id = $user->id;
            // Add Company Here...
            // $company = Company::where('title', $request['company_title'])->first();
            // if(empty($company)) {
            $company = new Company();
            $company->title = $request->input('company_title');
            $company->super_admin_id = $super_admin_id;
            $company->advocate_id =isset($request->advocate_id) && $request->advocate_id !=null && $request->advocate_id !='' ? $request->advocate_id : 0;
            $company->no_of_employee = empty($request->no_of_employee) ? 1 : $request->no_of_employee;
            $company->instance_id = $instance_id;
            $company->status = 0; // 0 = Active Company , 1 = Deactive. Java is doing this. :)
            $company->plan_id = $request->input('plan_id');
            $company->price_id = $request->input('price_id');
            $company->company_initial = '';
            $company->formation_type = $request->input('formation_type');
            $company->has_setup = 0;
            $company->subscription_id = "";
            if (!empty($request->company_initial)) {
                $company_initial = substr($request->company_initial, 0, 3);
            } else {
                $company_initial = substr($request->input('company_title'), 0, 3);
            }
            $company_initial = preg_replace('/[^A-Za-z0-9\-]/', '', $company_initial);
            $company_initial = trim($company_initial);
            if (empty($company_initial)) {
                $company_initial = Str::random(3);
            }
            $company->emp_code_format = strtoupper($company_initial) . '-001';
            $company->save();
            $company->company_initial = strtolower($company_initial) . '_' . $company->id;
            $company->save();
            // }

            // Assign company to the user.
            $uc = UserCompanies::where(['company_id' => $company->id])->first();
            $userCompany = false;
            if (empty($uc)) {
                $uc = new UserCompanies();
                $uc->user_id = $super_admin_id;
                $uc->company_id = $company->id;
                $uc->profile_name = 'Owner';
                $uc->profile_type = 'Owner';
                $uc->status = 'active';
                $uc->is_employee = 1;
                $uc->profile_id = 1;
                $uc->is_deleted = 0;
                $uc->created_at = Carbon::now();
                $uc->updated_at = Carbon::now();
                $uc->is_terminated = 0;
                $uc->save();
                $userCompany = true;
            }
            // Company Configrations
            $configratons = Configuration::get();
            $companyConfigraton = CompaniesConfiguration::where(['company_id' => $company->id])->first();
            if(empty($companyConfigraton)) {
                $companyConfigraton = new CompaniesConfiguration();
                $companyConfigraton->company_id = $company->id;
            }
            $configratonsFillable = $companyConfigraton->getFillable();
            foreach ($configratons as $key => $row) {
                if(in_array($row['config_key'],$configratonsFillable)) {
                    $companyConfigraton->{$row['config_key']} = $row['config_val'];
                }
            }
            $companyConfigraton->save();
            // End Company Configrations
            if ($request->formation_type == 'direct' || $request->formation_type == "invitation" || $request->formation_type == "web") {
                DB::commit();
                $status['status'] = true;
                $status['company'] = $company->toArray();
                $status['user'] = $user->makeHidden(['app_password', 'password', 'remember_token'])->toArray();
                $status['message'] = 'Company has be created in the master, without migrations';
                return response()->json($status, 200);
            }
            $status = $createNewDatabase->createNewDatabase($request, $company->id, $super_admin_id, $company->company_initial, null, $db_charset, $db_collation);

            Masterdb::connect_master_db();

            // $this->update_company_configuration($company->id);

            $status['company'] = $company->toArray();
            $status['user'] = $user->makeHidden(['app_password', 'password', 'remember_token'])->toArray();
            if ($status['status'] == true) {
                if ($request->user()) {
                    Mail::to($request->user()->email)->send(new AdminNotificationForCompanyCreate($user, $company));
                } else {
                    // Mail::to(getenv('SUPPORT_STAFFVIZ_EMAIL'))->send(new AdminNotificationForCompanyCreate($user, $company));
                    Mail::to([getenv('SUPPORT_STAFFVIZ_EMAIL'), getenv('CEO_EMAIL')])
                     ->send(new AdminNotificationForCompanyCreate($user, $company));
                }
                DB::commit();
            } else {
                DB::rollBack();
                $company->delete();
                if ($userCompany) {
                    $uc->delete();
                }
                if ($userMaster) {
                    $user->delete();
                }
                return response()->json([
                    "message" => $status['message'],
                    "errors" => [
                        "error" => [
                            $status['message']
                        ]
                    ]
                ], 500);
            }
            return response()->json($status, 200);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return response()->json([
                "message" => $e->getMessage(),
                "errors" => [
                    "error" => [
                        'Line: ' . $e->getLine() . ',Message: ' . $e->getMessage()
                    ]
                ]
            ], 500);
        }
    }

    public function update_company_configuration($company_id)
    {
        $comp = \DB::table('companies_configuration')
                   ->where('company_id',$company_id)
                   ->first();
        if ( empty($comp) ) {
            $config = $this->get_configuration();
            return DB::table('companies_configuration')->insert([
                            'company_id' => $company_id,
                            'trial_data_deletion_days' => $config['trial_data_deletion_days'],
                            'has_setup_deletion_days' => $config['has_setup_deletion_days'],
                            'timelogs_deletion_days' => $config['timelogs_deletion_days'],
                            'snapshot_deletion_days' => $config['snapshot_deletion_days'],
                            'active_free_plan_days' => $config['active_free_plan_days'],
                            'closeaccount_timelogs_deletion_days' => $config['closeaccount_timelogs_deletion_days'],
                            'closeaccount_snapshot_deletion_days' => $config['closeaccount_snapshot_deletion_days'],
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
        }
    }

    public function get_configuration()
    {
        $results = [];
        $comp = \DB::table('configurations')
                   ->get()
                   ->toArray();
        if ( is_array($comp) && count($comp) > 0 ) {
            foreach( $comp as $cc ) {
                $results[$cc->config_key] = $cc->config_val;
            }
            return $results;
        }
        return false;
    }

    public function inviteOwner(InviteOwnerRequest $request)
    {
        try {
            $fields = [
                "title",
                "company_initial",
                "user_id",
                "companies.id as company_id",
                "companies.formation_type as formation_type",
                "email",
                "first_name",
                "last_name",
                "profile_type",
                "profile_name",
                "companies_users.status",
                "is_terminated",
                "is_employee",
                "web_tracking",
                "password",
            ];
            $message = $request->message;
            $user = Company::CompanyOwner($request->company_id, $fields)->first();
            if ($user) {
                $user->agent_id = Auth()->user()->id;
                $userData = $user->toArray();

                // if(empty($userData['password'])) {
                //     $tinyUrl = $this->resetPassword($user->email);
                //     $user->login_url = getenv("APP_STAFFVIZ_URL") . '/forget/' . $tinyUrl;
                // } else {
                //     $user->login_url = getenv("APP_STAFFVIZ_URL") . '/login';
                // }

                $encryptionDecryption = new  EncryptionDecryption;
                $jsonData = json_encode(['company_id' => $request->company_id]);
                $res = $encryptionDecryption->encrypt($jsonData, 'ENCRYPTION_KEY_FOR_AGENT_LOGIN');

                $user->login_url = getenv("STAFFVIZ_FRONTEND_URL") . '/company-payment/' . $res;

                $emailConditions = ['service' => 'active_plan', 'active_plan' => 'payment_successful', 'status' => 1, 'days' => 100];
                $email = Email::where($emailConditions)->whereNull('deleted_at')->first();
                $name = $user->first_name . ' ' . $user->last_name;
                /**
                 * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                 * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                 */
                $search = ['[x_name]', '[x_link]'];
                $replace = [$name, $user->login_url];

                (new \App\Services\StripeService())->sendMail($search, $replace, $user->user_id, $user->email, $email);
//                $status = Mail::to($userData['email'])->send(new InviteOwner($user, $message));
                return $user->makeHidden(['app_password', 'password', 'remember_token'])->toArray();
            } else {
                return response()->json([
                    "message" => "The company Owner not found",
                    "errors" => [
                        "company_id" => [
                            "Owner not found."
                        ]
                    ]
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "errors" => [
                    "error" => [
                        $e->getMessage()
                    ]
                ]
            ], 500);
        }

    }

    public function resetPassword($email = '')
    {
        $returnJosn = false;
        if (empty($email)) {
            $email = request()->email;
            $returnJosn = true;
        }
        $db_connect = Generic::connect_master();
        $user = $db_connect->table('users')->where('email', $email)->first();
        if (empty($user)) {
            if ($returnJosn) {
                return response()->json([
                    "message" => 'User not found!',
                    "errors" => [
                        "error" => [
                            'User not found!',
                        ]
                    ]
                ], 404);
            } else {
                return '';
            }
        }
        $companies_ids = null;
        $token = Str::random(8);
        while ($db_connect->table('reset_password')->where('token', $token)->exists()) {
            // Regenerate the token if it already exists
            $token = Str::random(8);
        }
        $user_companies = $db_connect->table('companies_users')
            ->where('user_id', $user->id)
            ->selectRaw('GROUP_CONCAT(company_id) as companies')
            ->first();
        $tiny_url = md5(date('Y-m-d h:i:s'));
        $db_connect->table('reset_password')->updateOrInsert(
            [
                'email' => $user->email,
            ],
            [
                'user_id' => $user->id,
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );
        $data = [
            'companies' => $user_companies->companies
        ];
        $encryptionDecryption = new  EncryptionDecryption;
        $jsonData = json_encode($data);
        $res = $encryptionDecryption->encrypt($jsonData, 'ENCRYPTION_KEY_FOR_AGENT_LOGIN');

        $final_user = [
            'token' => $token,
            'id' => $user->id,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'companies_ids' => $res,    //Crypt::encryptString(json_encode($data))
        ];
        $companies_ids = explode(',', $user_companies->companies);

        $db_connect->table('companies_users')->where(['user_id' => $user->id])->whereIn('company_id', $companies_ids)->update([
            'tiny_url' => $tiny_url,
            'url_expiry' => json_encode([
                'token' => $token,
                'companies_ids' => $user_companies->companies,
                'encrypted_ids' => $res, //Crypt::encryptString(json_encode($data))
                'timestamp' => date('Y-m-d h:i:s'),
            ])
        ]);

        $final_user = (object)$final_user;
        if ($returnJosn) {
            return response()->json([
                "message" => 'success',
                "data" => [
                    "final_user" => $final_user,
                    "tiny_url" => $tiny_url,
                    "token" => $token
                ]
            ], 200);
        }
        return $tiny_url;
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
    public function update(Request $request, $id): array
    {
        try {
            $allow = [
                "title",
                "emp_code_format",
                "no_of_employee",
                "plan_id",
                "created_at",
                'updated_at',
                "status",
                "super_admin_id",
                "instance_id",
                "screen_capture_image_size",
                "screen_capture_duration",
                "logo",
                'timezone',
                'company_initial',
                "formation_type",
                "has_setup",
                "company_admin_emails",
            ];
            $company = Company::where('id', $id)->first();
            if ($company) {
                foreach ($request->all() as $key => $value) {
                    if (in_array($key, $allow)) {
                        $company->{$key} = $value;
                    }
                }
                $company->save();
            }

            return [
                'company' => $company
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
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
        $fields = !empty($request->fields) ? explode(',', $request->fields) : [];
        return Company::companyWithTotalUsers($fields)->get();
    }

    /**
     * get company with total number of users.
     * @param int $id
     * @return Renderable
     */
    public function companyWithUsers($id = 0, Request $request)
    {
        return Company::CompanyWithUsers($id, $request->fields, $request->search, $request->status)->paginate($request->limit ?? config('settings.record_per_page'));
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

    public function companyOwner($id = 0, Request $request)
    {
        try {
            $fields = [
                "user_id",
                "email",
                "first_name",
                "last_name",
                "phone",
                "image",
                "profile_type",
                "profile_name",
                "companies_users.status",
                "companies.plan_id",
                "companies.price_id",
                "companies.subscription_id",
                "companies.title",
                "companies.id",
                "is_terminated",
                "is_employee",
                "web_tracking",
                "formation_type",
            ];
            return Company::companyOwner($id, $fields)->first();

        } catch (\Throwable $th) {
            //throw $th;
        }
        return [
            'success' => true,
        ];
    }

    public function updateCompany(UpdateCompanyPostRequest $request)
    {
        Masterdb::connect_master_db();
        (new Company())->updateCompany($request);

        $company_initial = $request->company_initial;
        // Let's update Child Database
        Masterdb::connect_company_db($company_initial);
        $company = (new Company())->updateCompany($request, true);

        return response()->json(['success' => 'company db updated successfully', 'company' => $company], 200);
    }

    public function createChildCompany(CreateChildCompanyRequest $request, createNewDatabaseService $createNewDatabase)
    {

        DB::beginTransaction();
        $company = Company::find($request->company_id);
        $company->title = !empty($request->title) ? $request->title : $company->title;
        if (empty($company)) {
            return response()->json([
                "message" => 'Company Not Found!',
                "errors" => [
                    "error" => ['Company Not Found!']
                ]
            ], 500);
        }
        $user = UserTableMaster::find($company->super_admin_id);
        $user->ip_address = !empty($request->ip_address) ? $request->ip_address : '';
        $user->ip_location = !empty($request->ip_location) ? $request->ip_location : '';
        $collations = DB::select("SHOW COLLATION");
        $db_charset = 'utf8';
        $db_collation = 'utf8_unicode_ci';
        $key = array_search('utf8mb4_0900_ai_ci', array_column($collations, 'Collation'));
        if ($key == false) {
            if (str_contains($request->header('User-Agent'), 'Postman')) {

            } else {
                return response()->json([
                    "message" => 'Database collation : utf8mb4_0900_ai_ci not found. Please contact to server admin.',
                    "errors" => [
                        "error" => [
                            'Database collation : utf8mb4_0900_ai_ci not found. Please contact to server admin.'
                        ]
                    ]
                ], 500);
            }

        } else {
            $db_charset = $collations[$key]->Charset;
            $db_collation = $collations[$key]->Collation;
        }

        $status = $createNewDatabase->createNewDatabase($request, $company->id, $company->super_admin_id, $company->company_initial, null, $db_charset, $db_collation);
        // $status['status'] = true;
        Masterdb::connect_master_db();
        $status['company'] = $company->toArray();
        $status['user'] = $user->makeHidden(['app_password', 'password', 'remember_token'])->toArray();
        if ($status['status'] == true) {

            $company->save();
            if (!empty($company->subscription_id)) {
                $stripeService = new StripeService();
                $subscription = $stripeService->getSubscription($company->subscription_id)->toArray();
                $event["data"]["object"] = $subscription;
                $stripeWebHookService = new \App\Services\StripeWebhookService();
                $subscriptionUpdate = $stripeWebHookService->updateSubscription($event);
            }
            if ($request->user()) {
                Mail::to($request->user()->email)->send(new AdminNotificationForCompanyCreate($user, $company));
            } else {
                // Mail::to(getenv('SUPPORT_STAFFVIZ_EMAIL'))->send(new AdminNotificationForCompanyCreate($user, $company));
                Mail::to([getenv('SUPPORT_STAFFVIZ_EMAIL'), getenv('CEO_EMAIL')])
                     ->send(new AdminNotificationForCompanyCreate($user, $company));
            }
            DB::commit();
        } else {
            DB::rollBack();
            return response()->json([
                "message" => $status['message'],
                "errors" => [
                    "error" => [
                        $status['message']
                    ]
                ]
            ], 500);
        }
        return response()->json($status, 200);
    }

    public function companyInstance($id)
    {
        $company = Company::getCompanyInstance($id)->first();
        if (!empty($company)) {
            return response()->json($company, 200);
        } else {
            return response()->json([
                "message" => 'Not Found',
                "errors" => [
                    "error" => [
                        'Not Found',
                    ]
                ]
            ], 404);
        }

    }

    public function searchSubscriptionProduct(Request $request)
    {
        $subscription = null;
        $company = Company::searchSubscriptionProduct($request->all())->paginate($request->limit ?? config('settings.record_per_page'));

        if ($request->has('check_double_plan_activation') && $request->check_double_plan_activation == 1 && !empty($request->plan_id)) {
            $subscription = Subscriptions::where('plan_id', $request->plan_id)->paginate($request->limit ?? config('settings.record_per_page'));
            return response()->json(["company" => $company, "subscription" => $subscription], 200);
        }

        return response()->json($company, 200);
    }

    public function closure_plan_add($id)
    {
        $company = Company::find($id);
        $stripeService = new StripeService();
        $stripeService->updateSubscription($company->subscription_id, [
            [
                'pause_collection' => [
                    'behavior' => 'mark_uncollectible' // Docs: https://docs.stripe.com/billing/subscriptions/pause-payment#pausing-subscription-schedules
                ],
            ]
        ]);

        if ($company->plan_staus == 'trialing') {
            (new \App\Services\GracePeriod())->blockUsers($company, true);
            try {
                Masterdb::connect_master_db();
                $admins = $company->company_admin_emails != null ? $company->company_admin_emails['ids'] : [];
                //fetch email body based on these conditions from database
                $emailConditions = ['service' => 'trial', 'trial' => 'cancel', 'status' => 1, 'days' => '0'];
                $email = Email::where($emailConditions)->whereNull('deleted_at')->first();
                Log::debug("CANCEL_TRIAL_EMAIL_RAW_QUERY: " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());

                if (count($admins) > 0) {
                    foreach ($admins as $admin) {
                        $user = UserTableMaster::where(['id' => $admin])->first();
                        $name = $user->first_name . ' ' . $user->last_name;
                        /**
                         * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                         * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                         */
                        $search = ['[x_name]'];
                        $replace = [$name];
                        (new \App\Services\StripeService)->sendMail($search, $replace, $user->id, $user->email, $email);
                    }
                }


            } catch (\Exception $e) {
                Log::debug("CANCEL_TRIAL_EMAIL_ERROR: " . $e->getMessage());
                Log::debug("CANCEL_TRIAL_EMAIL_ERROR_LINE: " . $e->getLine());
            }
        }

        if (!$company->closure_plan && $company->plan_staus != 'trialing') {
            (new \App\Services\GracePeriod())->switchToClosurePlan($company);

            try {
                Masterdb::connect_master_db();

                $admins = $company->company_admin_emails != null ? $company->company_admin_emails['ids'] : [];
                //fetch email body based on these conditions from database
                $emailConditions = ['service' => 'active_plan', 'active_plan' => 'cancel_subscription', 'status' => 1, 'days' => '0'];
                $email = Email::where($emailConditions)->whereNull('deleted_at')->first();
                Log::debug("CANCEL_ACTIVE_TRIAL_EMAIL_RAW_QUERY: " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());
                if (count($admins) > 0) {
                    foreach ($admins as $admin) {
                        $user = UserTableMaster::where(['id' => $admin])->first();
                        $name = $user->first_name . ' ' . $user->last_name;
                        /**
                         * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                         * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                         */
                        $search = ['[x_name]'];
                        $replace = [$name];
                        (new \App\Services\StripeService)->sendMail($search, $replace, $company->super_admin_id, $user->email, $email);
                    }
                }


            } catch (\Exception $e) {
                Log::debug("CANCEL_ACTIVE_TRIAL_EMAIL_ERROR: " . $e->getMessage());
                Log::debug("CANCEL_ACTIVE_TRIAL_EMAIL_ERROR_LINE: " . $e->getLine());
            }
        }

        return response()->json($company, 200);

    }

    public function closure_plan_remove($id)
    {
        $company = Company::find($id);
        // $user = UserTableMaster::where("id" , $company->super_admin_id)->first();

        $stripeService = new StripeService();
        $subscription_id = null;
        $subscriptions = null;
        if ($company->closure_plan == 0) {
            $subscription_id = $company->subscription_id;
        } else {
            $subscription = Subscriptions::where(['company_id' => $company->id])->latest()->first();
            if (!empty($subscription)) {
                $subscription_id = $subscription->subscription_id;
            }
        }
        if (empty($subscription_id)) {
            return response()->json([
                "message" => 'Subscription id / Company Id Not Found',
                "errors" => [
                    "error" => [
                        'Subscription id / Company Id Not Found',
                    ]
                ]
            ], 404);
        }
        // Upcoming Invoice
        // $upComingInvoice = $stripeService->upcomingInvoice($user->stripe_customer_id, ['subscription' => $subscription_id]);
        // dd($upComingInvoice->toArray());
        $stripeService->updateSubscription($subscription_id, [
            [
                'pause_collection' => '',
                'billing_cycle_anchor' => 'now',
                'proration_behavior' => 'none'
            ]
        ]);

        if ($company->closure_plan) {
            (new \App\Services\GracePeriod())->unBlockUsers($company, $subscription);
        } else {
            (new \App\Services\GracePeriod())->unBlockUsers($company);
        }

        return response()->json($company, 200);

    }


    public function companySetupTrialOrActiveEmail(Request $request)
    {
        if ($request->has('user_id') && $request->has('type') && !empty($request->user_id)) {
            $type = $request->type;
            $user = UserTableMaster::where(['id' => $request->user_id])->first();

            if (!empty($type)) {
                //fetch email body based on these conditions from database
                $emailConditions = ['service' => $type, $type => 'compnay_setup', 'status' => 1, 'days' => '0'];
                $email = Email::where($emailConditions)->whereNull('deleted_at')->first();
                Log::debug("companySetupTrialOrActiveEmail_RAW_QUERY: " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());


                //Log::debug("START_TRIAL_EMAIL_QUERY_RESULT: " . $email != null ? json_encode($email->toArray()) : null);

                $name = $user->first_name . ' ' . $user->last_name;
                /**
                 * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                 * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                 */
                $search = ['[x_name]'];
                $replace = [$name];
                (new \App\Services\StripeService)->sendMail($search, $replace, $request->user_id, $user->email, $email);
            } else {
                Log::debug('companySetupTrialOrActiveEmail: Missing required flag type value trail or active_plan "');
                return false;
            }

        }
    }

    public function companyCloseAccount(CompanyCloseAccountPostRequest $request)
    {

        $company_id = $request['company_id'];
        $company_detail = Company::find($company_id);
        // Create a new record in the company_close_account table
        $companyCloseAccount = CompanyCloseAccount::create([
            'company_id' => $request['company_id'],
            'plan_type' => $company_detail->plan_status,
            'reason' => $request['reason'],
            'message' => $request['message'],
            'closing_time' => date('Y-m-d H:i:s'),
        ]);

        // Redirect to a specific page (e.g., index page) with a success message
        return response()->json(['success' => 'Company account closing information saved successfully.', 'data' => $companyCloseAccount]);
    }

    public function fetchCompanyCloseAccount(Request $request)
    {
        $filter = $request->filter;
        $filters = [
            0 => 'Trial Cancel',
            1 => 'Active Plan Cancel',
            2 => 'Payment Failed',
            3 => 'Insufficient Balance',
            4 => 'Post Grace'
        ];
        $query = CompanyCloseAccount::query();
        if (!empty($filter)) {
            $query->where('filter', $filter);
        }
        $closedAccounts = $query->paginate($request->limit ?? config('settings.record_per_page'));

        $companies = $closedAccounts != null ? $closedAccounts->pluck('company_id')->toArray() : [];
        $companyTitlesWithIds = [];
        if ($companies != null && count($companies) > 0) {
            $compData = Company::whereIn('id', $companies)->get();
            foreach ($compData as $company) {
                $companyTitlesWithIds[$company->id] = $company->title;
            }
        }
        return response()->json(['closedAccounts' => $closedAccounts, 'companyDetail' => $companyTitlesWithIds]);
    }



    public function deleteCompany($company_id){


            $company = Company::findOrFail($company_id); // Use findOrFail to avoid null check
            $user = User::find(Auth::id());

            // Check if the user has the 'superuser' role (Uncomment if needed)
            if ($user->superuser !=1) {
                return response()->json(['message' => 'You are not an authorized user.'], 403);
            }

            // Drop the database
            $databaseName = $company->company_initial;


            $endPoint= getenv('COMPANY_DELETE_ENDPOINT').'/'.$databaseName;
            // $endPoint= 'http://103.225.220.178:8089/api/trial/'.$databaseName;

            $response = Http::withHeaders([
                // 'token' => getenv('INTERNAL_SYSTEM_COMMUNICATION'),
            ])->acceptJson()
            ->timeout(300) // Increase timeout to 30 seconds
            ->delete($endPoint);
            if ($response->successful()) {
                 \Log::info('Storage deleted from AWS successfully: ' . $databaseName);
            } else {
                 \Log::info('Storage not deleted from AWS successfully: ' . $databaseName);
            }

                // Delete storage from local and aws
            if (getenv('STORAGE_DELETE_FROM_QA') == true) {

                $response = Http::withHeaders([
                    // 'token' => getenv('INTERNAL_SYSTEM_COMMUNICATION'),
                ])->acceptJson()
                ->get(getenv('WEB_PORTAL_API').'/delete-data-from-local-storage/'.$databaseName);
                if ($response->successful()) {
                     \Log::info('Storage deleted from AWS successfully: ' . $databaseName);
                } else {
                     \Log::info('Storage not deleted from AWS successfully: ' . $databaseName);
                }


            }

            return response()->json([
                'status'=>true,
                'message' => "Database `$company->company_initial` dropped successfully.",
            ]);


    }


    public function deleteCompanyBkup($company_id) //(CheckReqBeforeCompanyDelete $request)
{
    /**
     * Prerequisite: company_id, auth user id, confirmation before delete, validation
     */
    try {




        DB::beginTransaction(); // Start transaction

        // $company_id = $request->company_id;
        $company = Company::findOrFail($company_id); // Use findOrFail to avoid null check
        $user = User::find(Auth::id());

        // Check if the user has the 'superuser' role (Uncomment if needed)
        if ($user->superuser !=1) {
            return response()->json(['message' => 'You are not an authorized user.'], 403);
        }

        Masterdb::connect_master_db();

        // Delete users who are only associated with this company and not in multiple companies
        $user_list = UserCompanies::where('company_id', $company_id)->pluck('user_id')->toArray();

        UserTableMaster::whereIn('id', function ($query) use ($user_list) {
                    $query->select('user_id')
                        ->from('companies_users')
                        ->whereIn('user_id', $user_list) // Users of this company
                        ->groupBy('user_id')
                        ->havingRaw('COUNT(company_id) = 1'); // Users belonging only to this company
                })->delete();

        // Delete company records from UserCompanies table
        UserCompanies::where('company_id', $company_id)->delete();
        $company->delete(); // Delete company record
        // Fetch instance details
        $instance = Instance::decrypt()->latest()->where('id', $company->instance_id)->firstOrFail();

        DB::purge('tth_master_db'); // Clear any existing master database connection

        // Configure database connection dynamically
        $company_db_config = [
            'driver'   => 'mysql',
            'host'     => $instance->db_host,
            'port'     => $instance->db_port,
            'database' => 'information_schema', // Use a neutral database
            'username' => $instance->db_username,
            'password' => $instance->db_password,
        ];

        config(['database.connections.' . $company->company_initial => $company_db_config]);

        DB::purge($company->company_initial); // Clear any existing connection to the company DB
        DB::reconnect($company->company_initial); // Reconnect with new settings

        // Drop the database
        $databaseName = $company->company_initial;

        $exists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$databaseName]);

        if (!empty($exists)) {

            DB::connection($databaseName)->statement("DROP DATABASE `$databaseName`");

            // Delete storage from local and aws
            if (getenv('STORAGE_DELETE_FROM_AWS') == true) {

                    $response = Http::withHeaders([
                            // 'token' => getenv('INTERNAL_SYSTEM_COMMUNICATION'),
                        ])->acceptJson()
                        ->get(getenv('STORAGE_DELETE_FROM_AWS_ENDPOINT').'/delete-data-from-local-storage/'.$databaseName);
                        if ($response->successful()) {
                            \Log::info('Storage deleted from AWS successfully: ' . $databaseName);
                        } else {
                            \Log::info('Storage not deleted from AWS successfully: ' . $databaseName);
                        }


            } else {

                        $response = Http::withHeaders([
                            // 'token' => getenv('INTERNAL_SYSTEM_COMMUNICATION'),
                        ])->acceptJson()
                        ->get(getenv('WEB_PORTAL_API').'/delete-data-from-local-storage/'.$databaseName);
                        if ($response->successful()) {
                            \Log::info('Storage deleted from AWS successfully: ' . $databaseName);
                        } else {
                            \Log::info('Storage not deleted from AWS successfully: ' . $databaseName);
                        }
            }
        }



        DB::commit(); // Commit transaction

        return response()->json([
            'status'=>true,
            'message' => "Database `$company->company_initial` dropped successfully.",
        ]);
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback on failure
        return response()->json([
            'status'=>false,
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
