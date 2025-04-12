<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserTableMaster;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Services\EncryptionDecryption;
use App\Http\Requests\UserRegistrationFormRequest;
use App\Http\Requests\AddRememberTokenRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\UserCompanies;
use App\Models\ResetPassword;
use App\Models\Instance;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\MasterUsersUpdateFormRequest;
use App\Http\Requests\MasterUsersAddFormRequest;
use App\Libraries\Generic;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\User;
use Modules\Plans\Http\Controllers\PaymentMethodController;
// use \Illuminate\Http\Response;
use Validator;
use App\Libraries\Masterdb;

use Modules\Plans\Http\Controllers\SubscriptionController;


class StaffvizRegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Handle a registration request for the application.
     */
    public function register(UserRegistrationFormRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        event(new Registered($user));

        // $user = User::create($input);
        // $success['token'] =  $user->createToken($request->device)->plainTextToken;
        // $success['name'] =  $user->first_name . ' ' . $user->last_name;
        // return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required',
        ]);
        if($validator->fails()){
          return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = UserTableMaster::where(['email' => $request->email])->first();
        if($user) {
            if(Hash::check($request->password, $user->password)) {
                $profiledata = UserCompanies::where(['user_id' => $user->id, 'is_deleted' => 0, 'is_terminated' => 0,'status'=>'active'])->first();
                if (empty($profiledata)) {
                    return $this->sendError('Validation Error.', 'Your access has been revoked. Please contact your admin for further info.');
                }
                $user_comp = UserCompanies::where([
                        'companies_users.user_id' => $user->id,
                        'companies_users.is_deleted' => 0,
                        'companies_users.status' => 'active',
                        'companies_users.is_terminated' => 0
                    ])
                    ->join('companies','companies.id','=','companies_users.company_id')
                    ->join('instances','instances.id','=','companies.instance_id')
                    ->select([
                        'companies.*',
                        'companies_users.user_id',
                        'companies_users.profile_id',
                        'companies_users.profile_type',
                        'companies_users.profile_name',
                        'companies_users.web_tracking',
                        'companies_users.idle_time_tracking',
                        'companies_users.default_approval',
                        'instance',
                        DB::raw('AES_DECRYPT(db_host,"'.env('ENC_KEY').'") as db_host'),
                        DB::raw('AES_DECRYPT(db_port,"'.env('ENC_KEY').'") as db_port'),
                        DB::raw('AES_DECRYPT(db_username,"'.env('ENC_KEY').'") as db_username'),
                        DB::raw('AES_DECRYPT(db_password,"'.env('ENC_KEY').'") as db_password'),
                        DB::raw('AES_DECRYPT(db_driver,"'.env('ENC_KEY').'") as db_driver'),
                    ])
                    ->get();
                return $this->sendResponse([
                    'user' => $user->makeHidden(['password', 'app_password']),
                    'profiledata' => $profiledata,
                    'companies' => $user_comp,
                ], 'Login Successfully.');
            } else {
                return $this->sendError('Validation Error.', 'Invalid Email or Password',401);
            }
        } else {
            return $this->sendError('Validation Error.', 'Invalid Email or Password',401);
        }
    }

    public function get_user_company_list(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id'     => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user_comp = UserCompanies::where([
                        'companies_users.user_id' => $request->user_id,
                        'companies_users.is_deleted' => 0,
                        'companies_users.status' => 'active',
                        'companies_users.is_terminated' => 0
                    ])
                    ->join('companies','companies.id','=','companies_users.company_id')
                    ->join('instances','instances.id','=','companies.instance_id')
                    ->select([
                        'companies.*',
                        'companies_users.user_id',
                        'companies_users.profile_id',
                        'companies_users.profile_type',
                        'companies_users.profile_name',
                        'companies_users.web_tracking',
                        'companies_users.idle_time_tracking',
                        'companies_users.default_approval',
                        'instance',
                        DB::raw('AES_DECRYPT(db_host,"'.env('ENC_KEY').'") as db_host'),
                        DB::raw('AES_DECRYPT(db_port,"'.env('ENC_KEY').'") as db_port'),
                        DB::raw('AES_DECRYPT(db_username,"'.env('ENC_KEY').'") as db_username'),
                        DB::raw('AES_DECRYPT(db_password,"'.env('ENC_KEY').'") as db_password'),
                        DB::raw('AES_DECRYPT(db_driver,"'.env('ENC_KEY').'") as db_driver'),
                    ])->get();
        if ( !empty($user_comp) ) {
            return $this->sendResponse([
                'companies' => $user_comp,
            ],  'Result found.');
        } else {
            return $this->sendError('Validation Error.', 'No result found');
        }
    }

    public function rememberToken($tinyUrl) {
        $userCompanies = UserCompanies::where('tiny_url','=',trim($tinyUrl))->first();
        if(empty($userCompanies)) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        $user_info = json_decode($userCompanies->url_expiry);
        $total_company_users = UserCompanies::where('status','active')
        ->where('is_terminated',0)
        ->where('is_deleted',0)
        ->where('company_id',$userCompanies->company_id)
        ->count();
        if(isset($user_info->token)){
            $remember_token = $user_info->token;
            $token_varify = ResetPassword::where(['token' => $remember_token])->first();

            if(empty($token_varify)) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }
            $data=[
                'user_id' => $userCompanies->user_id,
                'token_varify'=>$token_varify,
                'profile_name' => $userCompanies->profile_name,
                'total_company_users'=> $total_company_users
            ];
        }else{
            $user = UserTableMaster::find($userCompanies->user_id)->makeHidden(['password', 'app_password']);
            $company = Company::where('id', $userCompanies->company_id)->first();
            $instance = DB::table('instances')
            ->where('id', $company->instance_id)
            ->select(DB::raw('AES_DECRYPT(db_host,"' . env('ENC_KEY') . '") as db_host,id,created_at,updated_at,
                AES_DECRYPT(db_port,"' . env('ENC_KEY') . '") as db_port,AES_DECRYPT(db_driver,"' . env('ENC_KEY') . '") as db_driver,
                AES_DECRYPT(db_username,"' . env('ENC_KEY') . '") as db_username,AES_DECRYPT(db_password,"' . env('ENC_KEY') . '") as db_password,
                AES_DECRYPT(db_username,"' . env('ENC_KEY') . '") as db_username, instance
                '))
            ->first();
            $data=[
                'user_id' => $userCompanies->user_id,
                'user'=>$user,
                'company'=>$company,
                'user_company'=>$userCompanies,
                'instance'=>$instance,
                'total_company_users'=> $total_company_users
            ];
        }


        return $data;
    }

    public function get_detail_of_ex_employee($tinyUrl) {
        $userCompanies = UserCompanies::where('tiny_url','=',trim($tinyUrl))->first();
        if(empty($userCompanies)) {
            return response()->json([ 'message' => 'User not found', ], 404);
        }
        $user_info = json_decode($userCompanies->url_expiry);
        $user = UserTableMaster::find($userCompanies->user_id)->makeHidden(['password', 'app_password']);
        $company = Company::where('id', $userCompanies->company_id)->first();
        $total_company_users = UserCompanies::where('status','active')
                                            ->where('is_terminated',0)
                                            ->where('is_deleted',0)
                                            ->where('company_id',$userCompanies->company_id)
                                            ->count();
        $data=[
            'user_id' => $userCompanies->user_id,
            'user'=> $user,
            'company'=> $company,
            'user_company'=> $userCompanies,
            'total_company_users'=> $total_company_users
        ];
        return $data;
    }


    public function userUpdate(MasterUsersUpdateFormRequest $request , $id): array{

        try {
            $allow = [
                'first_name',
                'last_name',
                'email',
                'phone',
                'activated',
                'password',
                'superuser',
                'allow_tracking',
                'privacy_policy_accepted',
                'rate',
                'bill',
                'image',
                'macAddress',
                'weekly_limit',
                'theme_color',
                'current_status',
                'current_task_id',
                'last_updated',
                'client_app_version',
                'timezone',
                'app_password',
                'locale',
                'street',
                'number',
                'box',
                'postal_code',
                'city',
                'country',
                'preferences',
                'api_token',
                'email_verified_at',
                'remember_token',
                'created_at',
                'updated_at'
            ];

            $user = UserTableMaster::where('id', $id)->first();

            if($user) {
                foreach ($request->all() as $key => $value) {
                    if($key == 'password') {
                        $user->{$key} = Hash::make($value);
                        $user->app_password = Generic::decryptAppPassword($value,$user->email);
                        UserCompanies::where(['user_id' => $user->id])->update([ 'tiny_url' => null, 'url_expiry' => null ]);
                    }
                    if(in_array($key, $allow) && $key != 'password') {
                        $user->{$key} = $value;
                    }
                }
                $user->save();
                $user->makeHidden(['password', 'app_password']);
            }
            return [
                'user' =>  $user ,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function userAdd(MasterUsersAddFormRequest $request): array{
        $user = new UserTableMaster();
        $allow = $user->getFillable();
        if($user) {
            foreach ($request->all() as $key => $value) {
                if($key == 'password') {
                    $user->{$key} = Hash::make($value);
                    $user->app_password = Generic::decryptAppPassword($value,$user->email);
                }
                if(in_array($key, $allow) && $key != 'password') {
                    $user->{$key} = $value;
                }
            }
            $user->stripe_customer_id = "";
            $user->save();
            $user->makeHidden(['password', 'app_password']);
        }
        return [
            'user' =>  $user ,
        ];
    }
    public function updateUserPassword(){
        
            $users = UserTableMaster::all();
            $password='12345678';
            foreach($users as $user){
                $user->password = Hash::make($password);
                $user->app_password = Generic::decryptAppPassword($password,$user->email);
                $user->save();
            }

        dd('Updated all passwords');
    }
    public function companyUser(Request $request , $company_id, $user_id): array{
        try {
            $allow = [
                "user_id", "company_id", "profile_id","tiny_url","url_expiry", "profile_name", "profile_type", "status", "is_deleted", "allow_tracking", "is_terminated", "status_comments", "is_employee", "capture_screen", "web_tracking", "idle_time_tracking", "default_approval"
            ];
            $user_company = UserCompanies::where(['company_id' => $company_id , 'user_id' => $user_id])->first();
            if(empty($user_company)) {
                $user_company = new UserCompanies();
                $user_company->company_id = $company_id;
                $user_company->user_id = $user_id;
            }
            foreach ($request->all() as $key => $value) {
                if(in_array($key, $allow)) {
                    $user_company->{$key} = $value;
                }
            }
            $user_company->save();
            return [
                'user_company' =>  $user_company ,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function terminate_active_company_users_bulk(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $company_id = $request->company_id;
            $company = Company::where('id', $company_id)->first();
            (new UserCompanies())->update_users_terminate( $company_id , $user_id, ['tiny_url' => null,'url_expiry' => null,'status' => 'active', 'is_terminated' => 0,'is_deleted' => 0] );
            Masterdb::connect_company_db($company->company_initial);
            DB::table('companies_users')->where('user_id', $user_id)
                                        ->where('company_id', $company_id)
                                        ->update([
                                            'status' => 'active',
                                            'is_terminated' => 0 ,
                                            'is_deleted' => 0
                                        ]);
            DB::table('candidate_info')->where('user_id', $user_id)
                                       ->update([
                                            'employment_status' => 'joined'
                                        ]);
            Masterdb::connect_master_db();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
     public function insert_user_master_and_company_db(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $company_id = $request->company_id;
            $shift_id = $request->shift_id;
            $first_name = $request->first_name;
            $last_name = $request->last_name;
            $password = $request->password;
            $phone = $request->phone;

            $user = UserTableMaster::where('id', $user_id)->first();

            if($user) {
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->password = Hash::make($password);
                $user->app_password = Generic::decryptAppPassword($password,$user->email);
                $user->save();
            }
            $company = Company::where('id', $company_id)->first();

            UserCompanies::where('user_id', $user_id)->where('company_id', $company_id)->update(['tiny_url' => null,'url_expiry' => null,'status' => 'active', 'is_terminated' => 0 , 'is_deleted' => 0]);
            Masterdb::connect_company_db($company->company_initial);

            DB::table('users')->where('id', $user_id)
            ->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'password' => Hash::make($password),
                // 'app_password' => Generic::decryptAppPassword($password, $$user->email),
                'phone' => $phone,
            ]);

            DB::table('companies_users')->where('user_id', $user_id)
                                        ->where('company_id', $company_id)
                                        ->update([
                                            'status' => 'active',
                                            'is_terminated' => 0 ,
                                            'is_deleted' => 0
                                        ]);
            DB::table('candidate_info')->where('user_id', $user_id)
                                       ->update([
                                            'employment_status' => 'joined',
                                            'primary_contact' => $phone,
                                        ]);

            $shift = DB::table('shifts')->where('id', $shift_id)->first();
            $shift_breaks = DB::table('shift_breaks')->where('shift_id', $shift_id)->get();
            $user_breaks=[];
            if ($shift){
                if (isset($shift_breaks) && count($shift_breaks) > 0) {
                    foreach ($shift_breaks as $break) {
                        if ($break->status==1){
                            $user_breaks[] = [
                                'user_id' => $user_id,
                                'scheduled_by' => $shift->id,
                                'break_start' => $break->start_time,
                                'break_end' => $break->end_time,
                                'break_type' => $shift->break_type,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ];
                        }

                    }
                }
                if (count($user_breaks) > 0){
                    DB::table('user_breaks')->insert($user_breaks);
                }
            }



            Masterdb::connect_master_db();
            return [
                'status' =>  true,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function terminate_company_users_bulk(Request $request)
    {
        try {
            $user_id = $request->user_id;
            UserCompanies::whereIn('user_id', $user_id)->update(['status' => 'deactive', 'is_terminated' => 1]);
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getUserByEmail($email) {
        $user = UserTableMaster::where(['email' => $email])->first();
        if(!empty($user)) {
            $user->makeHidden(['password', 'app_password']);
            return response()->json([
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

    }

    public function verifyUserWithCompanyExist(Request $request){
        $user = UserTableMaster::where(['email' => $request->email])->first();
        if(!empty($user)) {
            $user->makeHidden(['password', 'app_password']);
            $user_already_exist=UserCompanies::where(['user_id' => $user->id,'status'=>'active','is_deleted'=>0,'is_terminated'=>0])->exists();
            $companies=Company::where('super_admin_id',$user->id)->get()->toArray();
            return response()->json([
                'user' => $user,
                'companies'=>$companies,
                'user_already_exist'=>$user_already_exist
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }

    public function authenticateUser(Request $request){


        $user = UserTableMaster::where(['email' => $request->email])->first();
        if(!empty($user)) {

            if(Hash::check($request->password, $user->password)) {
                $user->makeHidden(['password', 'app_password']);
                $companies=Company::where('super_admin_id',$user->id)->get()->toArray();
                return response()->json([
                    'user' => $user,
                    'companies'=>$companies,

                ], 200);
            }else{

                return response()->json([
                    'message' => 'Incorrect credentials',
                ], 404);

            }

        } else {
            return response()->json([
                'message' => 'Incorrect credentials',
            ], 404);
        }
    }

    public function deleteCompany($company_id, $user_id) {

        $user_company = UserCompanies::where(['company_id' => $company_id , 'user_id' => $user_id])->first();
        if(!empty($user_company)) {
            $user_company->delete();
            Company::where(['id' => $company_id])->delete();
            return response()->json([
                'message' => 'Company deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 200);
        }
    }

    public function deletecompanyUser($company_id, $user_id) {
        $user_company = UserCompanies::where(['company_id' => $company_id , 'user_id' => $user_id])->first();
        if(!empty($user_company)) {
            $user_company->delete();
            return response()->json([
                'message' => 'User successfully deleted from company user',
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }

}
