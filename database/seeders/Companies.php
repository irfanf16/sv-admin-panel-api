<?php

namespace Database\Seeders;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Companies extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $companies = null;
        $latest_company = session('temp_company');
        
        //$instance = \DB::connection('mysql')->table('instances')->latest()->first();
        //$instance_id = $instance ? $instance->id : 1;
        $companies = array(
            array(
                'id'=>  $latest_company->id,
                'title' => $latest_company->title,
                'emp_code_format' => $latest_company->emp_code_format,
                'no_of_employee' => $latest_company->no_of_employee,
                'company_initial' => $latest_company->company_initial,
                'super_admin_id' => $latest_company->super_admin_id,
                'logo' => $latest_company->logo,
                "subscription_id" => $latest_company->subscription_id,
                "plan_id" => $latest_company->plan_id,
                "price_id" => $latest_company->price_id,
                "plan_expiry" => $latest_company->plan_expiry,
                "plan_staus" => $latest_company->plan_staus,
                "timezone_id" => "EST5EDT", 
                "theme" => "theme-two", 
                "enable_time_tracking" => 0,
                "enable_time_capture" => 0, 
                "enable_screen_capture" => 0, 
                "enable_web_app_tracking" => 0,
                "enable_idle_time" => 0, 
                "screen_capture_duration" => 10, 
                "screen_capture_image_size" => "medium",
                "screen_capture_limit" => 3,
                "theme_json" => json_encode([
                    'theme_name' => 'theme-two',
                    'primary_color' => "#FFFFFF",
                    // "secondary_color" => "#2892FD",
                    "secondary_color" => "#14487D",
                    'third_color' => '#2892FD',
                    'hover_color' => "#D4E9FF"
                ]), 
                "date_format" => "MM-DD-YYYY",
                "time_format" => 12, 
                "working_hours" => 8, 
                "emp_start_no" => 1,
                "emp_code_length" => 5 ,
                "has_setup" => 0,
                "currency_id" => 'US',
                "industry_id" => 1,
                "weekdays" => serialize(['Saturday', 'Sunday']),
                "idle_default_approval" => "accept",
                "idle_time_threshold" => "10",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )
        );
        
       
        Company::insert($companies);
    }
}
