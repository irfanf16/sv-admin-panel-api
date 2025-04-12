<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Libraries\Masterdb;
use App\Mail\UserNotificationMail; 
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CourseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $push_notification_ids;
    protected $course;
    protected $company_id;
    public function __construct($event, $push_notification_ids, $course, $company_id)
    {
        $this->event = $event;
        $this->push_notification_ids = $push_notification_ids;
        $this->course = $course;
        $this->company_id = $company_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->event == 'course_start') {
            Log::info('Database: ' . DB::getDatabaseName() . ' has connected');
            $company = DB::connection('mysql')->table('companies')->where('id', $this->company_id)->first();
            if (!empty($company)) {
                
                $db_host = getenv('DB_HOST');
                $db_port = getenv('DB_PORT');
                $db_username = getenv('DB_USERNAME');
                $db_password = getenv('DB_PASSWORD');
                $db_driver = getenv('DB_CONNECTION');
                Masterdb::connect_company_db_param($company->company_initial, $db_host, $db_port, $db_username, $db_password,$db_driver );
                Config::set("database.default", $company->company_initial);
                DB::reconnect();
                Log::info('Database: ' . DB::getDatabaseName() . ' has connected and trying to dispatch the job');
                $results = DB::table('push_notifications')
                ->wherein('id', $this->push_notification_ids)
                ->where('is_delivered', 0)
                ->get();
                
                if ($results->isNotEmpty()) {
                    // Update is_delivered
                    DB::table('push_notifications')
                    ->whereIn('id', $this->push_notification_ids)
                    ->update(['is_delivered' => 1]);
                    // End Update
                    // $results is not empty
                    $userIds = $results->pluck('user_id')->toArray();
                    $results->pluck('user_id');
                    // Fetch users' email addresses
                    $users = DB::table('users')
                        ->whereIn('id', $userIds)
                        ->select(['email', 'first_name', 'last_name'])
                        ->get();
                    // Send email to each user
                    foreach ($users as $user) {
                        $user_email = $user->email; 
                        // $user_email = 'adnan.crecentech@gmail.com'; 
                        Log::info('Email job dispatched');
                        Mail::to($user_email)->queue(new UserNotificationMail($this->event, $user, $this->course));
                    }
                } else {
                    Log::info('Database:' . DB::getDatabaseName() . ' Course Notification not found!');

                }
            }
        }
        
    }
}
