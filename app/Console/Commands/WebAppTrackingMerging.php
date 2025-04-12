<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\WebAppTrackingModel;

class WebAppTrackingMerging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:web-app-tracking-merging';

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
     */
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
                    Log::debug('Web & app Tracking update on company ' . $d->company_initial);
                    $this->line('Web & app Tracking update on company start at ' . date('Y-m-d H:i:s'). ' ' . $d->company_initial);
                    (new WebAppTrackingModel())->get_data($d->company_initial);
                    $this->line('Web & app Tracking update on company end at ' . date('Y-m-d H:i:s'). ' ' . $d->company_initial.' ---------------------------------------------');
                }
            }
            return 0;
        } catch (\Exception $e) {
            $this->error('Error : ' . $e->getMessage());
            Log::debug('Error List ' . $e->getMessage());
        }
    }
}