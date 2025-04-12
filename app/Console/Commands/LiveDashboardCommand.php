<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\LiveDashboardStatusModel;

class LiveDashboardCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live-dashboard-status-updated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update live dashboard status';

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
        try {
            set_time_limit(0);
            $db = \DB::table('companies')
                       ->select('companies.company_initial')
                       ->where('companies.status',0)
                       ->where('companies.has_setup',1)
                       ->get()
                       ->toArray();
            if ( is_array($db) && count($db) > 0 ) {
                $last_connection = null;
                foreach( $db as $d ) {
                    (new LiveDashboardStatusModel())->get_data($d->company_initial);
                    $this->line('Live dashboard status on company ' . $d->company_initial);
                }
            }
            return 0;
        } catch (\Exception $e) {
            $this->error('Error : ' . $e->getMessage());
            Log::debug('Error List ' . $e->getMessage());
        }
    }


}