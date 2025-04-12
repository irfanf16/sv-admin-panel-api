<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Config;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Libraries\Masterdb;
// use App\Services\createNewDatabaseService;

class RunMigrationsInSpecificDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */


    protected $signature = 'custommigrationtth {rollback?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            if ($this->confirm('Do you wish to continue?')) {
                set_time_limit(0);
                $company_initials = DB::connection('mysql')->table('companies')->get();
                $last_connection = null;
                foreach ($company_initials as $company) {

                    //check if database already exist otherwise skip loop iteration
                    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
                    $db = DB::select($query, [$company->company_initial]);

                    if (empty($db)) {
                        continue;
                    }

                    $company_initial = $company->company_initial;
                    $company = DB::connection('mysql')->table('companies')->where('company_initial', $company_initial)->first();
                    if (!empty($company)) {
                        $db_host = getenv('DB_HOST');
                        $db_port = getenv('DB_PORT');
                        $db_username = getenv('DB_USERNAME');
                        $db_password = getenv('DB_PASSWORD');
                        $db_driver = getenv('DB_CONNECTION');

                        if($last_connection != null){
                            \Illuminate\Support\Facades\DB::disconnect($last_connection);
                        }

                        Masterdb::connect_company_db_param($company_initial, $db_host, $db_port, $db_username, $db_password,$db_driver );
                        Config::set("database.default", $company_initial);
                        \Illuminate\Support\Facades\DB::reconnect();
                        $this->line('Database: ' . DB::getDatabaseName() . ' has updated');
                        if ($this->argument('rollback')) {
                            Artisan::call('migrate:rollback', ['--database' => $company_initial, '--force' => true]);
                        } else {
                            Artisan::call('migrate', ['--database' => $company_initial, '--force' => true]);
                        }
                    } else {
                        continue;
                    }
                    $last_connection = $company_initial;
                }
            }
            return 0;
        } catch (\Exception $e) {
            Log::debug('Company Name : ' . DB::getDatabaseName() . ', Error : ' . $e->getMessage());
            $this->error('error occurred in Company Name : ' . DB::getDatabaseName() . ', Error : ' . $e->getMessage());
        }
    }
}
