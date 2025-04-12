<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Libraries\Masterdb;
use App\Models\LiveDashboardStatusModel;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class LiveDashboardStatusModel extends Model
{
    use HasFactory;

    protected $table = 'users';

    public function get_data($company_initial=null)
    {
        return $this->get_web_app_tracking_data($company_initial);
    }

    private function get_web_app_tracking_data($company_id)
    {
        set_time_limit(0);
        Masterdb::connect_company_db($company_id);
        $timezone = $this->getCompanyTimeZone();
        $delete_id = [];
        $result = [];
        // Step 1: Disable `ONLY_FULL_GROUP_BY` mode
        \DB::statement("SET sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        // \DB::statement("SET SESSION group_concat_max_len = 200000");
        $sql = "SELECT 
                    id as user_id
                    FROM users
                    WHERE 
                        CONVERT_TZ(last_updated,'{$timezone}','UTC') <= CONVERT_TZ(NOW(),'{$timezone}','UTC') - INTERVAL 60 MINUTE";

        $db = \DB::select($sql);
        if ( is_array($db) && count($db) > 0 ) {
            foreach ( $db as $db_index => $db_row ) {
                $result[] = $db_row->user_id;
            }
            $this->update_status($result);
            return true;
        }
    }

    private function update_status($user_id)
    {
        $u = implode(',', $user_id);
        return \DB::table('users')->whereIn('id',$user_id)->update(['current_status' => 2]);
    }

    private function getCompanyTimeZone()
    {
        $db = \DB::table('companies')->first();
        if ( !empty($db) ) {
            return $db->timezone_id;
        }
        return NULL;
    }

    

}
