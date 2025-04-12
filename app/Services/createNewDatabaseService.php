<?php

namespace App\Services;


use App\Models\UserCompanies;
use App\Models\UserTableMaster;
use App\Models\Company;
use Carbon\Carbon;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use App\Mail\DatabaseCreationFailedNotification;
use Illuminate\Support\Facades\Mail;
// use App\Libraries\Generic;
// use App\Mail\VerifyMail;
use Illuminate\Support\Facades\DB;
use App\Models\Instance;
use Illuminate\Support\Facades\Log;
use App\Models\ModuleFeature;
class createNewDatabaseService implements createNewDatabaseInterface
{

    private function connect_master()
    {
        return DB::connection('mysql');
    }

    public function createNewDatabase($request, $company_id = 0, $user_id = 0, $company_initial = null, $instance = null, $db_charset = 'utf8mb4', $db_collation = 'utf8mb4_0900_ai_ci')
    {
        $user = UserTableMaster::where('id', $user_id)->first();
        $company = Company::where('id', $company_id)->first();
        $module_features_list = ModuleFeature::with(['featuresList'])->get()->toArray();
        session(['module_features_list' => $module_features_list]);
        try {
            $already_exists = UserCompanies::where(['company_id' => $company_id, 'user_id' => $user_id])->exists();
            UserCompanies::where(['company_id' => $company_id, 'user_id' => $user_id])->update(['status' => 'active']);

            if (!$already_exists) {
                $uc = new UserCompanies();
                $uc->user_id = $user_id;
                $uc->company_id =  $company_id;
                $uc->profile_name = 'Owner';
                $uc->profile_type = 'Owner';
                $uc->status = 'active';
                $uc->is_employee = 1;
                $uc->profile_id = 1;
                $uc->is_deleted = 0;
                $uc->created_at  = Carbon::now();
                $uc->updated_at  = Carbon::now();
                $uc->is_terminated = 0;
                $uc->save();
            }
            if(empty($instance)) {
                $instance = Instance::decrypt()->latest()->first();
            }
            $data = UserCompanies::where([
                'user_id' => $user_id,
                'company_id' => $company_id
                ])->first();
            $data = json_decode(json_encode($data), true);
            session([
                'temp_user' => $user->toArray(),
                'temp_comp' => $data,
                'temp_company' => $company,
            ]);
            $dbConnection = [
                "database" => $company_initial,
                "driver" => getenv('DB_CONNECTION'),
                "host" => getenv('DB_HOST'),
                "port" => getenv('DB_PORT'),
                "username" => getenv('DB_USERNAME'),
                "password" => getenv('DB_PASSWORD'),
                "charset" => $db_charset,
                "collation" => $db_collation,
                "prefix" => "",
                "strict" => false,
                "engine" => null
            ];

            DB::statement("DROP DATABASE IF EXISTS ".$company_initial." ");
            DB::statement("CREATE DATABASE IF NOT EXISTS ".$company_initial." ");

            // Note: if db user is the root and instance user is also the root then db becomes currupt. This is happing in my local server. To prevent this issue following check is added.
            if(getenv('DB_USERNAME') != $instance->db_username) {
                // Giving acccess of Database to the instance User.
                DB::statement("CREATE USER IF NOT EXISTS ".$instance->db_username."@'%' identified by '".$instance->db_password."'");
                DB::statement("GRANT SELECT,INSERT,UPDATE,DELETE,EXECUTE ON ".$company_initial.".* TO ".$instance->db_username."@'%'");
                DB::statement("flush privileges");
            }
            config(["database.connections.".$company_initial => $dbConnection]);
            Config::set("database.default", $company_initial);

            DB::reconnect();
            $db = DB::select("SHOW TABLES");

            if (empty($db)) {
                set_time_limit(0);
                ini_set('memory_limit', '-1');
                //Running artisan commands for creating new db and dummy data
                Artisan::call('migrate', ['--database' => $company_initial, '--force' => true]);
                Artisan::call('db:seed', ['--database' => $company_initial, '--force' => true]);

                session()->forget('temp_company');
                session()->forget('temp_comp');
                session()->forget('temp_user');

                // UserCompanies::where(['company_id' => $company_id, 'user_id' => $user_id])->update(['status' => 'active']);
                session()->forget('module_features_list');
                return [
                    'status' => true,
                    'message' => 'Created Successfully.',
                ];
            } else {
                session()->forget('module_features_list');
                $db_connect = $this->connect_master();
                $db_connect->table('companies_users')->where(['company_id' => $company_id, 'user_id' => $user_id])->update(['status' => 'active']);
                return [
                    'status' => false,
                    'message' => 'Company already exists.',
                ];
            }

        } catch (\Exception $e) {
            session()->forget('module_features_list');
            DB::statement("DROP DATABASE IF EXISTS ".$company_initial." ");
            try {
                $db_connect = $this->connect_master();
                $user = $db_connect->table('users')->where('id',$user_id)->first();
                $company = $db_connect->table('companies')->where('id',$company_id)->first();
                $username = $user->first_name.' '.$user->last_name;
                $email = $user->email;
                $company_title = $company->title;
                $reference_number = $company_initial.'-001';
                $reason =  $e->getMessage();

                //send notification to admin or high authority
                if(Auth()->user()) {
                    Mail::to(Auth()->user()->email)->send(new DatabaseCreationFailedNotification($username, $email, $reference_number, $company_title, $reason));
                } else {
                    Mail::to(getenv('SUPPORT_STAFFVIZ_EMAIL'))->send(new DatabaseCreationFailedNotification($username, $email, $reference_number, $company_title, $reason));
                }
                
                return [
                    'status' => false,
                    'message' => $reason,
                ];
            } catch (\Exception $th) {
                Log::debug('FailedDatabaseCreationEmail: '.$th->getMessage() .':::Controller Line: '.$th->getLine());
            }
            Log::debug('createNewDatabaseService: '.$e->getMessage() .':::Controller Line: '.$e->getLine());
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }

    }
}
