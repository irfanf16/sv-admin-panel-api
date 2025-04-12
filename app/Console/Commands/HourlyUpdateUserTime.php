<?php

namespace App\Console\Commands;

use App\Libraries\Generic;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Libraries\Masterdb;

class HourlyUpdateUserTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:hourlyCalculateUserTime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command update user hourly time in summary tables of every company';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {
            $company_initials = DB::connection('mysql')->table('companies')->latest()->get();
            $last_connection = null;
            foreach ($company_initials as $company) {

//              disconnect the last database
                if ($last_connection != null) {
                    \Illuminate\Support\Facades\DB::disconnect($last_connection);
                }

                //check if database already exist otherwise skip loop iteration
                $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
                $db = DB::select($query, [$company->company_initial]);
                if ($db) {
                    $company_initial = $company->company_initial;
                    $connection = DB::connection('mysql')->table('instances')->where('id', $company->instance_id)
                        ->select(\DB::raw('AES_DECRYPT(db_host,"' . env('ENC_KEY') . '") as db_host,id,created_at,updated_at,
                        AES_DECRYPT(db_port,"' . env('ENC_KEY') . '") as db_port,AES_DECRYPT(db_driver,"' . env('ENC_KEY') . '") as db_driver,
                        AES_DECRYPT(db_username,"' . env('ENC_KEY') . '") as db_username,AES_DECRYPT(db_password,"' . env('ENC_KEY') . '") as db_password,
                        AES_DECRYPT(db_username,"' . env('ENC_KEY') . '") as db_username, instance
                        '))->first();

                    $connection = (array)$connection;
                    $db_host = !empty($connection['db_host']) ? $connection['db_host'] : '';
                    $db_port = !empty($connection['db_port']) ? $connection['db_port'] : '';
                    $db_username = !empty($connection['db_username']) ? $connection['db_username'] : '';
                    $db_password = !empty($connection['db_password']) ? $connection['db_password'] : '';
                    $db_driver = !empty($connection['db_driver']) ? $connection['db_driver'] : '';

                    Masterdb::connect_company_db_param( $company_initial, $db_host, $db_port, $db_username, $db_password,$db_driver );

                    Config::set("database.default", $company_initial);
                    \Illuminate\Support\Facades\DB::reconnect();
                    Generic::hourlyCalculateUserTime();
                    $last_connection = $company_initial;

                    $this->info('Hourly records update on company ' . $company->company_initial);
                    Log::debug('Hourly records update on company ' . $company->company_initial);

                } else {
                    $this->error('company not found ' . $company->company_initial);
                    Log::debug('company not found ' . $company->company_initial);
                }

            }
             $this->info('Command Run');
        } catch (\Exception $e) {
            $this->error('Error : ' . $e->getMessage());
            Log::debug('Company Name : ' . DB::getDatabaseName() . ', Error : ' . $e->getMessage());
        }

    }
}
