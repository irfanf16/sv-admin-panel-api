<?php

namespace Database\Seeders;

use App\Models\Company\WebAppTrackingMeta;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebApptrackingMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('web_app_tracking_meta')->truncate();
        $records = [
            [
                'app' => 'firefox.exe', 
                'description'=> 'Mozilla Firefox',
                'type' => 1,
                'productivity' => 1,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],
            [
                'app'=>'chrome.exe', 
                'description'=> 'Google Chrome',
                'type' => 1,
                'productivity' => 1,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],
            [
                'app'=>'Google Chrome.exe', 
                'description'=> 'Google Chrome',
                'type' => 1,
                'productivity' => 1,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],
            [
                'app'=>'Safari.exe', 
                'description'=> 'Safari',
                'type' => 1,
                'productivity' => 1,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],
            [
                'app'=>'msedge.exe', 
                'description'=> 'Microsoft Edge',
                'type' => 1,
                'productivity' => 1,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],
            [
                'app'=>'opera.exe', 
                'description'=> 'Opera',
                'type' => 1,
                'productivity' => 1,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],
            [
                'app'=>'brave.exe', 
                'description'=> 'Brave Browser',
                'type' => 1,
                'productivity' => 1,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],
            [
                'app'=>'vivaldi.exe', 
                'description'=> 'Vivaldi',
                'type' => 1,
                'productivity' => 1,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],
            [
                'app'=>'tor.exe', 
                'description'=> 'Tor Browser',
                'type' => 1,
                'productivity' => 1,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]
        ];

        WebAppTrackingMeta::insert($records);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
