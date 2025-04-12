<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use DB;
use Illuminate\Support\Facades\Log;
use App\Libraries\Masterdb;
use Database\Seeders\ModuleSeeder;
class DependentModuleId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DependentModuleId {seederName?}';

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

    public function handle()
    {
        DB::beginTransaction();
        try {
            if ($this->confirm('Do you wish to continue?')) {
                
                set_time_limit(0);
                $modules = (new ModuleSeeder())->getModules();
                $updates = [];
                foreach ($modules as $key => $module) {
                    if($module['dependent_module_id'] != 0) {
                        $updates[$module['dependent_module_id']][] = $module['id'];
                    }
                }
                if(empty($updates)) {
                    $this->line('No data found in ModuleSeeder');
                }
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
                    if(empty($company)) {
                        continue;
                    }
                    $db_host = getenv('DB_HOST');
                    $db_port = getenv('DB_PORT');
                    $db_username = getenv('DB_USERNAME');
                    $db_password = getenv('DB_PASSWORD');
                    $db_driver = getenv('DB_CONNECTION');
                    if($last_connection != null){
                        \Illuminate\Support\Facades\DB::disconnect($last_connection);
                    }
                    Masterdb::connect_company_db_param($company_initial, $db_host, $db_port, $db_username, $db_password);
                    Config::set("database.default", $company_initial);
                    \Illuminate\Support\Facades\DB::reconnect();
                    foreach ($updates as $dependent_module_id => $module_ids) {
                        DB::connection($company_initial)->table('modules')
                            ->wherein('id', $module_ids)
                            ->update([
                                'status' => 1,
                                'dependent_module_id' => $dependent_module_id,
                            ]);
                    }
                    $this->line('Database: ' . DB::getDatabaseName() . ' has updated.');
                    $last_connection = $company_initial;
                }
            }

        } catch (\Exception $e){

            DB::rollback();
            Log::debug('Company Name : '.DB::getDatabaseName().', Error : '.$e->getMessage());
            $this->error('error occurred in Company Name : '.DB::getDatabaseName().', Error : '.$e->getMessage().' Line'.$e->getLine());
        }

    }
}
