<?php

namespace App\Libraries;


use Illuminate\Support\Facades\Log;
use Session;
use App\User;
use DateTime;
use App\Group;
use Carbon\Carbon;
use App\Department;
use App\Models\Module;
use App\Models\Company;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Timelog;
use App\Models\Instance;
use App\Models\ProfileTypes;
use App\Models\GalleryModel;
use Illuminate\Http\Request;
use App\Models\UserCompanies;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


/**
 *
 */
class Generic
{

    public static function getWorkedHours($start_time, $end_time)
    {
        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);
        $minutes = $end->diffInMinutes($start);
        return $minutes;
    }

    public static function getWorkedHoursInSecond($start_time, $end_time)
    {
        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);
        $minutes = $end->floatDiffInSeconds($start);
        return $minutes;
    }

    public static function get_shift_time_difference($start_time, $end_time)
    {
        $timezone = self::timeZone();
        $date = new \DateTime("now", new \DateTimeZone($timezone) );
        $timeformat = $date->format('H:i:s');
        
        if ( $start_time < $end_time ) {
            $start = Carbon::parse($start_time);
            $end = Carbon::parse($timeformat);
            $minutes = $end->floatDiffInSeconds($start);
            return $minutes;
        } else {
            $first_start_time = self::getWorkedHoursInSecond($start_time, '23:59:59');
            $second_start_time = self::getWorkedHoursInSecond('00:00:00', $timeformat);
            return ( $first_start_time + $second_start_time +1 );
        }
    }

    public static function get_shift_remaing_time($start_time)
    {
        $timezone = self::timeZone();
        $date = new \DateTime("now", new \DateTimeZone($timezone));
        $end = Carbon::parse($date->format('H:i:s'));
        $start = Carbon::parse($start_time);
        $minutes = $end->floatDiffInSeconds($start);
        return self::secondsInHoursMinutesSeconds($minutes);
    }

    public static function get_remaining_time($time)
    {
        $timezone = self::timeZone();//Generic::timeZone($request);
        $date = new \DateTime("now", new \DateTimeZone($timezone));
        $time = Carbon::parse($date->format('H:i:s'))->floatDiffInSeconds($time);
        return self::secondsInHoursMinutesSeconds($time);
    }

    public static function get_remaining_time_shift_break($start_time, $end_time)
    {
        $new_end_time = self::secondsInHoursMinutesSeconds($end_time);
        $start = Carbon::createFromTimeString($start_time); //init the start time
        $end = Carbon::createFromTimeString($new_end_time); //init the end time
        $time = Carbon::parse($end->format('H:i:s'))->floatDiffInSeconds($start);
        return self::secondsInHoursMinutesSeconds($time);
    }

    public static function set_time_string($end_time)
    {
        $arr = explode(':', $end_time);
        if (count($arr) === 3) {
            return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
        }
        return $arr[0] * 60 + $arr[1];
    }

    public static function show_default_perm($module_name, $perm, $role_type = 1)
    {
        switch ($role_type) {
            case 2:
                return Perm::manager_perm($module_name, $perm);
                break;

            case 3:
                return Perm::viewer_perm($module_name, $perm);
                break;

            case 4:
                return Perm::users_perm($module_name, $perm);
                break;

            default:
                return Perm::perm($module_name, $perm);
                break;
        }
    }


    public static function dateformat($date)
    {
        if (!session('dateformat')){
            $dateformat = Company::where('id', session('company_id'))
                ->pluck('date_format')->first();
            session(['dateformat' => $dateformat]);

        }else{
            $dateformat=session('dateformat');
        }

        switch ($dateformat) {
            case "MM-DD-YYYY":
                $dateformat = "m-d-Y";
                break;
            case "YYYY-MM-DD":
                $dateformat = "Y-m-d";
                break;
            default:
                $dateformat = "d-m-Y";
        }
        $dateformat = date($dateformat, strtotime($date));
        return $dateformat;
    }

    public static function companytimeformat($date)
    {
        $dateformat = Company::where('id', session('company_id'));
        $timeformat = $dateformat->pluck('time_format')->first();
        if ($timeformat == 12) {
            $dateformat = date('h:i A', $date);
        } else {
            $dateformat = date('H:i', $date);//$dateformat . " | H:i";
        }
        return $dateformat;
    }

    public static function timeformat($date)
    {
        // $date=mktime($date);
        // dd($date);
        $dateformat = Company::where('id', session('company_id'));
        $timeformat = $dateformat->pluck('time_format')->first();
        $dateformat = $dateformat->pluck('date_format')->first();

        switch ($dateformat) {
            case "MM-DD-YYYY":
                $dateformat = "m-d-Y";
                break;
            case "YYYY-MM-DD":
                $dateformat = "Y-m-d";
                break;
            default:
                $dateformat = "d-m-Y";
        }
        if ($timeformat == 12) {
            $dateformat = $dateformat . " | h:i A";
        } else {
            $dateformat = $dateformat . " | H:i";
        }
        $dateformat = date($dateformat, strtotime($date));


        return $dateformat;
    }

    public static function setupProfileSession($company_id = 0, $profiledata = null, $first_name_user = null, $last_name_user = null, $id = null, $users = null, $validator = null
        ,                                      $super_admin = null)
    {
        session(['company_id' => $company_id]);
        $company = Company::find($company_id);
        $super_admin = $company->super_admin_id;
        $logo_name = $company->logo;
        $instance = Instance::decrypt()->where('id', $company->instance_id)->first();
        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
        $db = DB::select($query, [$company->company_initial]);

        if (empty($db)) {
            Auth::logout();
            \Cookie::queue("deactive", "Company login failed.", 1);
            return redirect('/login');
        }

        config(["database.connections." . $company->company_initial => [
            // fill with dynamic data:
            "driver" => !empty($instance->db_driver) ? $instance->db_driver : 'mysql',
            "host" => !empty($instance->db_host) ? $instance->db_host : '',
            "port" => !empty($instance->db_port) ? $instance->db_port : '',
            "database" => $company->company_initial,
            "username" => !empty($instance->db_username) ? $instance->db_username : '',
            "password" => !empty($instance->db_password) ? $instance->db_password : '',
            "charset" => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix" => "",
            "strict" => false,
            "engine" => null
        ]]);

        Config::set("database.default", $company->company_initial);
        \Illuminate\Support\Facades\DB::reconnect();

        $u_data = User::where('email', $users->email)->first();


        $profiledata = UserCompanies::where(['user_id' => $u_data->id, 'is_deleted' => 0, 'status' => 'active', 'is_terminated' => 0])->first();

        if (empty($profiledata)) {
            Auth::logout();
            \Cookie::queue("deactive", "Your account has been terminated. Please contact your admin.", 1);
            return redirect('/login');
        }

        $profile_id = $profiledata->profile_id;

        $profile = Profile::find($profile_id);

        $profile_title = $profile->title;

        $profile_types = ProfileTypes::all();

        $p_types = [];
        if ($profile_types) {
            $profile_types = $profile_types->toArray();
            foreach ($profile_types as $key => $profile_type) {
                $p_types[$profile_type['id']] = $profile_type;
                $p_types[$profile_type['id']]['title'] = strtolower($profile_type['title']);
            }
        }
        $timezone = $company->timezone ?? config('app.timezone');

        session([
            'CompanyName' => $company->title,
            'FirstName' => $first_name_user,
            'LastName' => $last_name_user,
            'profile_title' => $profile_title,
            'user_id' => $id,
            'theme_color' => $u_data->theme_color,
            'profile_id' => $profiledata->profile_id,
            'profile_type' => $profile->profile_type,
            'profile_types' => $p_types,
            'timezone' => $timezone,
            'super_admin' => $super_admin != null && $super_admin == $users->id ? 1 : 0,
            'owner_id' => $super_admin
        ]);

        Cache::put("$company->id-imezone", $timezone);
        
        $path = GalleryModel::where('path' , $logo_name)->first();
        if ( $logo_name !== NULL && empty($path) ) {
            $data = [ 
                    'extension' => 0 , 
                    'mimetype' => 0, 
                    'filesize' => 0 , 
                    'width' => 0 , 
                    'height' => 0 , 
                    'path' => $logo_name , 
                    'name' => 'Company Logo' , 
                    'folder_id' => 'all'
                ];
            $insert_id = ( new \App\Models\GalleryModel() )->insertImage($data);
            $image_file_name = url('/files/'.$insert_id);
            (new Company())->update_setting( session('company_id'), ['logo' => $image_file_name ] );
        }

        $permissions = Generic::getProfilePermissions(session('profile_id'));
        Cache::store('file')->put('profile_per_' . session('company_id') . '_' . session('profile_id'), $permissions);

        return true;
    }

    //    public static function switchCompany()
    //    {
    //
    //        $connection = 'mysql';
    //        if (session('temp_company') == true && !is_null(session('temp_company'))) {
    //            $data = session('temp_company');
    //            $connection = 'company_' . $data->id;
    //        } elseif (session('company_id')) {
    //
    //            $connection = 'company_' . session('company_id');
    //
    //        }
    //
    //        return $connection;
    //
    //    }

    public static function changeDateTimeZone($datetime, $previous_timezone, $new_timezone)
    {
        $time = new DateTime($datetime, new \DateTimeZone($previous_timezone));
        $time->setTimezone(new \DateTimeZone($new_timezone));
        $time = $time->format('H:i:s');
        return $time;
    }

    public static function creatNewChildDatabase()
    {
    }

    public static function setConnection($tenantName)
    {
        //GET Database Connection file path
        $path = config_path('connections.php');

        //GET Database Connection file
        $arr = include $path;
        if (array_key_exists($tenantName, $arr) == false) {
            // load the array from the file
            $new_connection = [
                'driver' => 'mysql',
                'host' => '',
                'port' => '',
                'database' => $tenantName,
                'username' => '',
                'password' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => NULL
            ];
            // modify the array
            $arr[$tenantName] = $new_connection;
            // write it back to the file
            file_put_contents($path, "<?php  return  " . var_export($arr, true) . ";");
        }
    }

    public static function format_time($t, $f = ':') // t = seconds, f = separator
    {
        $hour = floor($t / 3600);
        $hour = $hour <= 9 ? '0' . $hour : $hour;
        return sprintf("%02d%s%02d%s%02d", $hour, $f, ($t / 60) % 60, $f, $t % 60);
    }

    public static function export_format_time($t, $f = ':') // t = seconds, f = separator
    {
        $hour = floor($t / 3600);
        $hour = $hour <= 9 ? '0' . $hour : $hour;
        return sprintf("%02d%s%02d%s%02d", $hour, $f, ($t / 60) % 60, $f, $t % 60);
    }
    // public static function sformat_time($t, $f = ':') // t = seconds, f = separator
    // {
    //     return sprintf("%02d%s%02d%s%02d", floor($t / 3600), $f, ($t / 60) % 60, $f, $t % 60);
    // }

    public static function sformat_time($seconds)
    {

        $hours = floor($seconds / 3600);

        $minutes = floor(($seconds - $hours * 3600) / 60);
        $seconds = floor($seconds - ($hours * 3600) - ($minutes * 60));
        $hours = $hours < 10 ? '0' . $hours : $hours;
        $minutes = $minutes < 10 ? '0' . $minutes : $minutes;
        $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

        return $minutes . ':' . $seconds;
    }

    public static function getUnreadNotificationNumbers()
    {

        //        dd(session('profile_id'),Auth::user()->id);
        $user = User::find(Auth::user()->id);
        if ($user) {
            $user = User::find(Auth::user()->id)->unreadNotifications()->get();
            return count($user);
        } else {
            return 0;
        }


    }

    public static function department($id = null)
    {
        $name = Department::select('department_name')->where('id', $id)->first();
        return $name->department_name;
    }

    public static function AllNotificationCount()
    {
        $user_id = Auth::user()->id;
        if ($user_id) {
            $noti = (new \App\Models\PushNotifications())->getUserAllNotificationsCount($user_id);
            return $noti;
        } else {
            return 0;
        }

    }

    public static function getCurrentPerm()
    {
        return (new \App\Models\ProfileModules())->getModulesPerm();
    }

    public static function userModules()
    {
        return (new Module())->children_module(self::owner_profile());
        // if (!empty(session('modules')))
        // return session('modules');

        // $profile = Profile::find(session('profile_id'))->modules()->where([
        //     ['permanent_disabled', '=', 0], ['status', '=', 0], ['profile_id', '=', session('profile_id')],
        //     ['permission_id', '=', 3], ['module_order', '!=', ''], ['module_type', '=', 1]
        // ])->orderBy('module_order', 'asc')->get();

        // $modules = [];
        // $submodules_to_be_deleted = array();
        // foreach ($profile as $item) {

        //     $m_submenu = Profile::find(session('profile_id'))->modules()->where([
        //         ['permanent_disabled', '=', 0], ['parent_module_id', '=', $item->id], ['status', '=', 0],
        //         ['profile_id', '=', session('profile_id')], ['permission_id', '=', 3], ['module_type', '=', 1]
        //     ])->orderBy('module_order', 'asc')->get();


        //     $modules_childs = [];
        //     $sub_modules_url = [];
        //     foreach ($m_submenu as $submenu) {

        //         array_push($modules_childs, [
        //             'title' => $submenu->title,
        //             'id' => $submenu->id,
        //             'url' => $submenu->url,
        //             'icon' => $submenu->icon,
        //         ]);
        //         array_push($sub_modules_url, $submenu->url);
        //         array_push($submodules_to_be_deleted, $submenu->id);
        //     }
        //     array_push($modules, [
        //         'title' => $item->title,
        //         'id' => $item->id,
        //         'url' => $item->url,
        //         'icon' => $item->icon,
        //         'submodules' => !empty($modules_childs) ? $modules_childs : [],
        //         'submodules_url' => !empty($sub_modules_url) ? $sub_modules_url : []
        //     ]);
        //     unset($modules_childs);
        //     unset($sub_modules_url);
        // }

        // if (!empty($submodules_to_be_deleted)) {
        //     foreach ($modules as $key => $module) {
        //         if (in_array($module['id'], $submodules_to_be_deleted)) {

        //             unset($modules[$key]);
        //         }
        //     }
        // }
        // session(['modules', $modules]);
        // return $modules;
    }


    public static function managerProjectOrGroupUsers($user_id = null, $company_id = null)
    {
        $manger_projects = \DB::table('user_projects')->where(['user_id' => $user_id, 'profile_id' => 2])->pluck('project_id');
        $manger_projects = $manger_projects != null && $manger_projects->count() > 0 ? $manger_projects->toArray() : [];

        if (count($manger_projects) > 0) {
            $manger_users = \DB::table('user_projects')->whereIn('project_id', $manger_projects)->pluck('user_id');
            $manger_users = $manger_users != null && $manger_users->count() > 0 ? array_unique($manger_users->toArray()) : [];
        } else {
            $manger_users = [];
        }


        return $manger_users;
    }

    public static function reportsMenu()
    {

        $modules_dropdown = Module::where('parent_module_id', '>', '0')->get();
        $menus = [];
        foreach ($modules_dropdown as $dropdown) {
            array_push($menus, [
                'id' => $dropdown->parent_module_id,
                'title' => $dropdown->title,
                'url' => $dropdown->url,
            ]);
        }
        return $menus;
    }

    public static function isAllowed($moduleId, $permissionId)
    {
        //        dd(session('profile_id'));
        if (Auth::guest()) {
            return false;
        }

        $user_modules = Profile::find(session('profile_id'))->modules()->get();
        $permissions = Profile::find(session('profile_id'))->permissions()->get();
        foreach ($user_modules as $module) {
            foreach ($permissions as $permission) {
                if ($moduleId == $module->id and $permissionId == $permission->id) {
                    $moduleId == $module->id and $permissionId == $permission->id;
                    return true;
                }
            }
        }

        return false;
    }

    public static function url(Request $request)
    {
        $user_modul = Profile::find(session('profile_id'))->modules()->get();

        //        $check_id= [];
        //        foreach ($user_modul as $module) {
        //
        //            array_push($check_id, [
        //                'title' => $module->title,
        //                'url' => $module->url,
        //                'icon' => $module->icon
        //                ]);
        //            return true;

        //            dd($module->pivot->profile_id );
        //            dd(session('profile_id'));
        //
        //            if (session('profile_id') == 1) {
        //                return true;
        //            }
        //            else if (session('profile_id') != 1){
        //                return false;
        ////            }
        //        }


        //        $user_modul = Profile::find(session('profile_id'))->modules()->get();
        //        foreach ($user_modul as $module) {
        //            if($request->is($module->url)) {
        //                return true;
        //            }
        //
        //        }
    }


    public static function showMessage($message = "", $type = 'info')
    {
        $html = "";
        if ($message != "") {
            $html = '<div class="alert alert-' . $type . ' fade in">
                       <button data-dismiss="alert" class="close close-sm" type="button">
                            <i class="fa fa-times"></i>
                       </button>
                       ' . $message . '
                   </div>';
        }
        return $html;
    }

    public static function breadcrumbs($params = array('admin'))
    {
        $str = array();
        foreach ($params as $key) {
            if ($key == 'admin') {
                $str[] = "<a href=" . url('/') . ">Admin</a>";
            } elseif ($key == 'users') {
                $str[] = "<a href=" . url('users') . ">Users</a>";
            } elseif ($key == 'departements') {
                $str[] = "<a href=" . url('dtypes') . ">Departments</a>";
            } else {
                $str[] = ucwords($key);
            }
        }
        return implode(' > ', $str);
    }

    public static function formateDateDB($date)
    {
        return date('Y-m-d h:i:s a', strtotime($date));
    }

    public static function formateDateUser($date)
    {
        if ($date == "" or $date == "0000-00-00")
            return "-";
        return date('d-m-Y', strtotime($date));
    }

    public static function intoPostMeRiDiem($time)
    {

        $time = date("H:i", strtotime($time . 'pm'));
        return $time;
    }

    public static function combineDateAndTime($date, $time)
    {

        return date('Y-m-d H:i:s', strtotime("$date $time"));
    }

    public static function timeDiffHoursMinutes($start_time, $end_time)
    {

        $startTime = Carbon::parse($start_time);
        $endTime = Carbon::parse($end_time);
        $totalDuration = $endTime->diffInHours($startTime);

        return $totalDuration;
    }

    public static function timeDiffMinutes($start_time, $end_time)
    {

        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);
        $minutes_duration = $end->diffInMinutes($start);
        return $minutes_duration;
    }

    public static function timeDiffHours($start_time, $end_time)
    {

        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);
        $hours_duration = $end->diffInHours($start);
        return $hours_duration;
    }

    public static function timeDiffSeconds($start_time, $end_time)
    {

        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);
        $seconds_duration = $end->diffInSeconds($start);

        return $seconds_duration;
    }

    public static function timeDifferenceSeconds($start_time, $end_time)
    {
        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);
        $diff = $end->diff($start);
        $seconds_duration = $diff->format('%s');
        return $seconds_duration;
    }

    public static function secondsInHoursMinutes($start_time, $end_time)
    {
        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);
        if ($end < $start) {
            $end->addDay();
        }
        $seconds_duration = $end->diff($start)->format('%H:%I');
        $seconds_duration = explode(':', $seconds_duration);
        return $seconds_duration;
    }

    public static function secondsInHoursMinutesSeconds($seconds)
    {
        if ( $seconds > 0 ) {
            $hours = floor($seconds / 3600);

            $minutes = floor(($seconds - $hours * 3600) / 60);
            $seconds = floor($seconds - ($hours * 3600) - ($minutes * 60));
            $hours = $hours < 10 ? '0' . $hours : $hours;
            $minutes = $minutes < 10 ? '0' . $minutes : $minutes;
            $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

            return $hours . ':' . $minutes . ':' . $seconds;
        } else {
            return '00:00:00';
        }
    }

    public static function getProfilePermissions($profile_id = null)
    {
        if (self::owner_profile() == 6) {
            $results = DB::select('SELECT 
                                        modules.title as module_title, 
                                        profiles.title as profile_title,
                                            GROUP_CONCAT(permissions.type) as permission_names, 
                                            GROUP_CONCAT(profile_modules.status) as permission_status, 
                                        profile_modules.module_id, 
                                        profile_id
                                    FROM `profile_modules` 
                                    JOIN permissions ON permissions.id = profile_modules.permission_id 
                                    JOIN modules ON modules.id=profile_modules.module_id 
                                    JOIN profiles ON profiles.id = profile_modules.profile_id  
                                    GROUP BY profile_modules.module_id,modules.title,profiles.title,profile_modules.profile_id',
                []
            );
        } else {
            $results = DB::select('SELECT 
                                        modules.title as module_title, 
                                        profiles.title as profile_title,
                                            GROUP_CONCAT(permissions.type) as permission_names, 
                                            GROUP_CONCAT(profile_modules.status) as permission_status, 
                                        profile_modules.module_id, 
                                        profile_id
                                    FROM `profile_modules` 
                                    JOIN permissions ON permissions.id = profile_modules.permission_id 
                                    JOIN modules ON modules.id=profile_modules.module_id 
                                    JOIN profiles ON profiles.id = profile_modules.profile_id  
                                    WHERE profile_id = :pid GROUP BY profile_modules.module_id,modules.title,profiles.title,profile_modules.profile_id',
                ['pid' => $profile_id]
            );
        }
        $permissions = array();
        foreach ($results as $result) {
            $names = explode(',', $result->permission_names);
            $statuses = explode(',', $result->permission_status);

            $temp = array();
            if (self::owner_profile() == 6) {
                foreach ($names as $i => $name) {
                    $temp[strtolower($name)] = 0;
                }
            } else {
                foreach ($names as $i => $name) {
                    $temp[strtolower($name)] = $statuses[$i];
                }
            }

            $permissions[strtolower($result->module_title)] = $temp;
        }
        return $permissions;
    }

    public static function calculateUserRate($seconds, $rate)
    {
        $hours = floor($seconds / 3600);
        $hours_rate = $hours * $rate;
        $minutes = floor(($seconds - $hours * 3600) / 60);
        $minutes_rate = $minutes / 60 * $rate;
        $seconds = floor($seconds - ($hours * 3600) - ($minutes * 60));
        $seconds_rate = $seconds / 3600 * $rate;
        $total_rate = intval($hours_rate + $minutes_rate + $seconds_rate);

        return $total_rate;
    }

    public static function secondsToHours($seconds)
    {

        $hours = intval($seconds / 3600);
        $minutes = floor(($seconds - $hours * 3600) / 60);
        $minutes = $minutes % 60;

        if (empty($seconds)) {

            return 0;
        }

        return $hours . '.' . $minutes;

    }

    public static function liveDashboardUpdateTime($seconds)
    {
        $timezone = self::timeZone();
        $date = new \DateTime("now", new \DateTimeZone($timezone));
        $today_date = $date->format('Y-m-d H:i:s');
        $timeslot = 0;
        if ($seconds != null) {
            $start_t = strtotime($today_date);
            $end_t = strtotime($seconds);
            $timeslot = ($end_t > $start_t ? ($end_t - $start_t) : ($start_t - $end_t));
        }
        return self::format_time($timeslot);
    }

    public static function secondsToHoursDatable($seconds)
    {
        if ($seconds > 0) {
            $secs = $seconds % 60;
            $hrs = $seconds / 60;
            $mins = (int)($hrs % 60);

            $hrs = (int)($hrs / 60);


            return ($hrs <= 9 ? "0" . $hrs : $hrs) . ':' . ($mins <= 9 ? "0" . $mins : $mins);
        } else {
            return "00:00";
        }
    }

    public static function secondsToHoursExcel($seconds)
    {
        if ($seconds > 0) {
            $secs = $seconds % 60;
            $hrs = $seconds / 60;
            $mins = $hrs % 60;

            $hrs = $hrs / 60;

            if ((int)$hrs == 0 || (int)$hrs == 1) {
                $hours_display = (int)$hrs . ", ";
            } else {
                $hours_display = (int)$hrs . ", ";
            }
            return $hours_display . (int)$mins . ", " . (int)$secs . ' sec';
        }
    }

    public static function getUserImage()
    {
        $user = User::find(Auth::id());
        return !empty($user->image) ? $user->image : '';
    }

    public static function getCompanyImage($company_id)
    {

        $company = Company::find($company_id);
        return $t = !empty($company->logo) ? Storage::disk('s3')->get('tth/' . $company->logo) : '';
    }

    public static function getCompanyCurrency()
    {
        $data_country = file_get_contents(url('/public/currency.json'));
        $new = [];

        foreach (json_decode($data_country, true) as $d => $c) {
            $code = explode('_', $c);
            $new[$d] = $code[2];
        }

        $company = Company::where('id', session('company_id'))->first();
        if ($company && $company->currency_id) {
            return $new[$company->currency_id];
        }
    }

    public static function currency_format($number = 0)
    {
        return self::getCompanyCurrency() . ' ' . number_format($number, 2, '.', ',');
    }

    public static function get_currency_format()
    {
        return self::getCompanyCurrency();
    }

    public static function getLastActivityOn($project_id, $activity_id)
    {
        $last_activity_on = Timelog::selectRaw('max(start_time) as start_time')
            ->where(['project_id' => $project_id, 'activity_id' => $activity_id])
            ->first();

        return $last_activity_on->start_time;
    }


    public static function getProjectCost($project_id = 0, $user_id = 0)
    {
        if ($user_id) {
            $cost = Timelog::selectRaw("
                    COALESCE(SUM(CASE WHEN timelogs.type IN ('A','M') THEN timelogs.cost END),0) 
                    +
                    COALESCE(SUM(CASE 
                    WHEN (timelogs.type = 'I' AND timelogs.admin_idle_approval IS NOT NULL AND timelogs.admin_idle_approval = 'A') 
                       THEN  timelogs.cost
                    ELSE
                       CASE 
                       WHEN (timelogs.type = 'I' AND timelogs.default_idle_approval IS NOT NULL AND timelogs.default_idle_approval = 'A' AND (timelogs.admin_idle_approval != 'd' or timelogs.admin_idle_approval IS NULL)) 
                       THEN
                        timelogs.cost
                       END
                    END),0) as time_log_cost")
                ->where('project_id', $project_id)
                ->where('user_id', $user_id)
                ->groupBy('project_id')
                ->whereIn('type', ['A', 'M', 'I'])
                ->first();
        } else {
            $cost = Timelog::selectRaw("
                    COALESCE(SUM(CASE WHEN timelogs.type IN ('A','M') THEN timelogs.cost END),0) 
                    +
                    COALESCE(SUM(CASE 
                    WHEN (timelogs.type = 'I' AND timelogs.admin_idle_approval IS NOT NULL AND timelogs.admin_idle_approval = 'A') 
                       THEN  timelogs.cost
                    ELSE
                       CASE 
                       WHEN (timelogs.type = 'I' AND timelogs.default_idle_approval IS NOT NULL AND timelogs.default_idle_approval = 'A' AND timelogs.admin_idle_approval != 'd') 
                       THEN
                        timelogs.cost
                       END
                    END),0) as time_log_cost")
                ->where('project_id', $project_id)
                ->groupBy('project_id')
                ->whereIn('type', ['A', 'M', 'I'])
                ->first();
        }


        return ($cost) ? $cost->time_log_cost : 0;
    }


    public static function timeZone($company_id)
    {
        $company_timezone = DB::table('companies')->where('id', $company_id)->first();
        if (!empty($company_timezone)) {
            return $company_timezone->timezone_id;
        } else {
            return config('app.timezone', 'UTC');
        }

    }


    public static function decryptAppPassword($value, $email)
    {
        $key = $email . '::' . $value;
        $query = DB::select('SELECT AES_ENCRYPT(? , md5(?)) as app_pass', [$value, $key]);
        if (count($query) > 0) {
            return $query[0]->app_pass;
        }
    }

    private static function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    public static function random_color()
    {
        return self::random_color_part() . self::random_color_part() . self::random_color_part();
    }

    public static function base64ToImage($path, $imageData)
    {
        list($type, $imageData) = explode(';', $imageData);
        list(, $extension) = explode('/', $type);
        list(, $imageData) = explode(',', $imageData);
        $fileName = uniqid() . '_' . time() . '.' . $extension;
        $filepaths = $path . '/' . $fileName;
        $imageData = base64_decode($imageData);
        file_put_contents($filepaths, $imageData);
        return $fileName;
    }

    public static function base64PathToImage($path, $imageData, $type, $fileName)
    {
//        list($type, $imageData) = explode(';', $imageData);
        // list(,$extension) = explode('/',$type);
//        list(,$imageData)      = explode(',', $imageData);
//        $fileName = uniqid() . '_' . time() .'.'.$extension;
        $filepaths = $path . '/' . $fileName;
        $imageData = base64_decode($imageData);
        file_put_contents($filepaths, $imageData);
        return $fileName;
    }

    public static function checkUserRolePermission($project_id)
    {
        // $user_id = Auth::id();
        $profile_type = Generic::owner_profile();
        if ($profile_type == 6){
            return $profile_type;
        }
        $user_profile = (new \App\Models\UserProjects())->getUserProfile($project_id, Auth::id());
        return isset($user_profile->profile_id) ? $user_profile->profile_id : null;
    }

    public static function checkUserGroupPermission($project_id)
    {
        $group_id = (new \App\Models\ProjectGroups())->getUserProjects($project_id, Auth::id());
        if ($group_id > 0 && $group_id == Auth::id()) {
            return 1;
        }
        return 0;
    }

    public static function filterData($data)
    {
        return trim(strip_tags($data));
    }

    public static function filterText($data)
    {
        return trim(htmlentities($data));
    }

    public static function changeDate($date)
    {
        $day = date('l, d M Y', strtotime($date));
        $time = date('h:i A', strtotime($date));
        return $day . ' at ' . $time;
    }

    public static function live_dashboard_user_status($status)
    {
        switch ($status) {
            case 2:
                print '<div class="user-ss user-absent">Absent</div>';
                break;

            case 3:
                print '<div class="user-ss user-active" style="background-color: #35A476;">Active</div>';
                break;

            case 4:
                print '<div class="user-ss user-idle">Idle</div>';
                break;

            case 5:
                print '<div class="user-ss user-break" style="background-color: #FE9319;">On Break</div>';
                break;
            case 1:
                print '<div class="user-ss user-inactive" style="background-color: #979494;">Inactive</div>';
                break;

            case 7:
                print '<div class="user-ss user-inactive" style="background-color: #4B7C8B;">Off Shift</div>';
                break;

            case 8:
                print '<div class="user-ss user-inactive" style="background-color: #4B7C8B;">Short Hours</div>';
                break;
        }
    }

    public static function live_dashboard_user_status_change($status)
    {
        switch ($status) {
            case 'Absent':
                print '<div class="user-ss user-absent" style="background-color: rgba(253, 206, 202, 1);color:#000000;">Absent</div>';
                break;

            case 'Active':
                print '<div class="user-ss user-active" style="background-color: rgba(195, 228, 214, 1);color:#000000;">Active</div>';
                break;

            case 'ActiveOffShift':
                print '<div class="user-ss user-active" style="background-color: rgba(195, 228, 214, 1);color:#000000;">Active</div>';
                break;

            case 'Idle':
                print '<div class="user-ss user-idle" style="background-color: rgba(227, 228, 227, 1);color:#000000;">Idle</div>';
                break;

            case 'On Break':
                print '<div class="user-ss user-break" style="background-color: rgba(255, 235, 213, 1);color:#000000;">On Break</div>';
                break;
            case 'InActive':
                print '<div class="user-ss user-inactive" style="background-color: rgba(220, 232, 227, 1);color:#000000;">InActive</div>';
                break;

            case 'Off Shift':
                print '<div class="user-ss user-inactive" style="background-color: rgba(183, 203, 209, 1);color:#000000;">Off Shift</div>';
                break;

            case 8:
                print '<div class="user-ss user-inactive" style="background-color: #4B7C8B;">Short Hours</div>';
                break;
        }
    }

    public static function encrypt_id($value)
    {
        $plaintext = $value;
        $key = 'tth_web_keys';
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
        return $ciphertext;
    }

    public static function decrypt_id($ciphertext)
    {
        $c = base64_decode($ciphertext);
        $key = 'tth_web_keys';
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        if (hash_equals($hmac, $calcmac))// timing attack safe comparison
        {
            return $original_plaintext;
        }
    }

    public static function connect_master()
    {
        return DB::connection('mysql');
    }

    public static function company_logo()
    {
        return (new Company())->getLogo();
    }

    public static function owner_profile()
    {
        if (session('super_admin') == 1) {
            $profie_type = 6;
        } else {
            $profie_type = session('profile_type');
        }
        return $profie_type;
    }

    public static function user_profile_name()
    {
        if (self::owner_profile() == 6) {
            return 'Owner';
        } else {
            return session('profile_title');
        }
    }

    public static function encode_base($encoded)
    {
        return str_rot13($encoded);
    }

    public static function current_pages($total, $current)
    {
        if ( $total > 0 ) {
            return ceil($total / $current);
        } else {
            return 0;
        }
    }

    public static function initWebSocket()
    {
        try {
            $host = '192.168.6.25';
            $port = 8083;
            $path = '/ws';


            $socket = fsockopen($host, $port, $errno, $errstr);
            if (!$socket) {

            }

            $key = '753dec6b-b600-40c0-a9f9-bc32cdc9906f';
            $headers = "GET $path HTTP/1.1\r\n";
            $headers .= "Host: $host\r\n";
            $headers .= "Upgrade: websocket\r\n";
            $headers .= "Connection: Upgrade\r\n";
            $headers .= "Sec-WebSocket-Key: Bearer" . $key . "\r\n";
            $headers .= "Sec-WebSocket-Version: 13\r\n\r\n";

            fwrite($socket, $headers);

            $response = '';
            while (!feof($socket)) {
                $response .= fgets($socket, 1024);
                if (strpos($response, "\r\n\r\n") !== false) {
                    break;
                }
            }
            if (!preg_match('/Sec-WebSocket-Accept:\s(.*)$/mU', $response, $matches)) {

            }
            $acceptKey = trim($matches[1]);

            $expectedResponse = sha1($key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true);

            if ($acceptKey !== $expectedResponse) {
                // handle error

            }

            // send message
            $message = json_encode([
                "company_id" => 23,
                "payload" => [
                    "audience" => "user/company",
                    "message" => "Hello",
                    "userIs" => [1, 2, 3]
                ]
            ]);
            $frame = chr(129) . chr(strlen($message)) . $message;

            fwrite($socket, $frame);

            // receive message
            $response = '';
            while (!feof($socket)) {
                $response .= fgets($socket, 1024);
                if (strpos($response, chr(129)) !== false) {
                    break;
                }
            }
            $data = substr($response, 2);

// close connection
            $closeFrame = chr(136) . chr(0);
            fwrite($socket, $closeFrame);
            fclose($socket);

            echo $data . ' ' . $response;
            die();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }


    }

    public static function notification_body_old($event_code, $id = 0, $title = null, $message = null, $params = [], $userIds = [], $timezone)
    {
        $data = [];
        $data['companyId'] = session('company_id');
        $message_body = [];
        $message_body['event_code'] = $event_code;
        $message_body['id'] = $id;
        $message_body['title'] = $title;
        $message_body['description'] = $params;
        $message_body['notification_date_time'] = $timezone;
        $data['payload'] = ['audience' => 'user', 'message' => $message_body, 'userId' => $userIds];
        $data1 = $data;
        $data = json_encode($data);
        $response = Generic::send_notification($data);
        if ($response) {
            if ($response->responseDescription == 'Success' || $response->responseCode == '00') {
                $push_notifications = [];
                foreach ($userIds as $userId) {
                    $push_notifications[] = [
                        'user_id' => $userId,
                        'message' => $message,
                        'message_type' => 1,
                        'view_option' => 1,
                        'view_hide_date' => 1,
                        'view_profile' => 1,
                        'read_status' => 1,
                        'source_type' => 1,
                        'target_app' => 1,
                    ];
                }
                if (count($push_notifications) > 0) {
                    DB::table('push_notifications')->insert($push_notifications);
                }
            } else {
                dd($response);
            }
        } else {
            Log::debug('push_notifications' . $data);
            dd($response);
        }
//        dd($data1, $data, $response);
    }

    public static function subscribe_notification($email, $password)
    {
        // data post into raw type
        $end_point = getenv('NOTIFICATION_IP');
        $port = getenv('NOTIFICATION_PORT');
        $path = 'api?service.key=login';

        if(getenv('NOTIFICATION_ENVIRONMENT') && getenv('NOTIFICATION_ENVIRONMENT') == 'dev'){
            $url = $end_point . ':' . $port . '/' . $path;
        }else{
            $url = $end_point . '/' . $path;
        }

        // save auth key into session
        // session key name => notification_auth_key
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"email":"' . $email . '","password":"' . $password . '"}',
            CURLOPT_HTTPHEADER => array('Content-Type:application/json'),));
        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);
        return $response;
    }

    public static function notification_body($event_code, $id = 0, $title = null, $message = null, $params = [], $userIds = [], $timezone, $notification_params = [])
    {
        try {
            $response_code = null;
            if (count($notification_params) > 0) {
                $message_type = $notification_params['message_type'];
                $view_option = $notification_params['view_option'];
                $view_hide_date = $notification_params['view_hide_date'];
                $view_profile = $notification_params['view_profile'];
                $read_status = $notification_params['read_status'];
                $source_type = $notification_params['source_type'];
                $target_app = $notification_params['target_app'];
            } else {
                $message_type = 1;
                $view_option = 1;
                $view_hide_date = 1;
                $view_profile = 1;
                $read_status = 1;
                $source_type = 1;
                $target_app = 1;
            }
            if (count($userIds) > 0) {
                foreach ($userIds as $userId) {

                    $user_Id = [];
                    $user_Id[] = $userId;
                    $push_notifications = [
                        'user_id' => $userId,
                        'message' => $title,
                        'message_type' => $message_type,
                        'view_option' => $view_option,
                        'view_hide_date' => $view_hide_date,
                        'view_profile' => $view_profile,
                        'read_status' => $read_status,
                        'source_type' => $source_type,
                        'target_app' => $target_app,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    $push_notification_id = DB::table('push_notifications')->insertGetId($push_notifications);
                    $data = [];
                    $data['companyId'] = session('company_id');
                    $message_body = [];
                    $message_body['event_code'] = $event_code;
                    $message_body['id'] = $id;
                    $message_body['title'] = $title;
                    $message_body['push_notification_id'] = $push_notification_id;
                    $message_body['description'] = $params;
                    $message_body['notification_date_time'] = $timezone;
                    $data['payload'] = ['audience' => 'user', 'message' => $message_body, 'userId' => $user_Id];
                    $data = json_encode($data);
                    if ($push_notification_id) {
                        DB::table('push_notifications')->where('id', $push_notification_id)->update(['message' => $data]);
                    }
                    $response = Generic::send_notification($data);
                    $response_code = $response;

                    if (isset($response) && $response != null) {
                        if (isset($response->responseDescription) && !$response->responseDescription == 'Success' || isset($response->responseCode) && !$response->responseCode == '00') {
                            DB::table('push_notifications')->where('id', $push_notifications)->delete();
                        }
                    } else {
                        DB::table('push_notifications')->where('id', $push_notifications)->delete();
                    }
                }

            }
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getTrace(), $response_code);
        }
    }

    public static function send_notification($data = [])
    {
        $end_point = getenv('NOTIFICATION_IP');
        $port = getenv('SEND_NOTIFICATION_PORT');
        $path = 'notification/send';

        if(getenv('NOTIFICATION_ENVIRONMENT') && getenv('NOTIFICATION_ENVIRONMENT') == 'dev'){
            $url = $end_point . ':' . $port . '/' . $path;
        }else{
            $url = $end_point . '/' . $path;
        }

        // get notification_auth_key from session
        $notification_auth_key = getenv('NOTIFICATION_AUTH_KEY');


        $fields = $data;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                'AUTH_KEY: ' . $notification_auth_key . '',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);
        return $response;

    }

    public static function getTotalTileAttempt($arr = [])
    {
        $total = 0;
        $time_array = [];
        // Loop the data items
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $element) {
                $time_array[] = $element['completion_time'];
            }
            // Explode by separator :
            asort($time_array);
            $temp = explode(":", end($time_array));
            // Convert the hours into seconds
            // and add to total
            $total += (int)$temp[0] * 3600;
            // Convert the minutes to seconds
            // and add to total
            $total += (int)$temp[1] * 60;
            // Add the seconds to total
            $total += (int)$temp[2];
            // dd($time_array);
            // Format the seconds back into HH:MM:SS
            $formatted = sprintf('%02d:%02d:%02d',
                ($total / 3600),
                ($total / 60 % 60),
                $total % 60);
            return $formatted;
        } else {
            return '00:00:00';
        }
    }

    public static function getQuizAnswer($arr = [])
    {
        $time_array = [];
        // Loop the data items
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $element) {
                $time_array[$element['question_id']] =[
                    'answer_id'=>$element['answer_id'],
                    'quiz_answers'=>$element['quiz_answers'],
                    ];
            }
        }
        return $time_array;
    }

    public static function getAllProjects()
    {
        $profile_type = Generic::owner_profile();

        $projects_ids = null;
        $user_id = Auth::id();
        if ($profile_type == 2) {
            $projects_ids = (new \App\Models\Project())->managerProjectsOrTasksOrCoursesIds($user_id, session('company_id'), 'projects');

        }
        $query = 'SELECT id, uuid, title from projects ';
        if ($profile_type == 2) {
            if ($projects_ids == null){
                $projects_ids=[0];
            }
            $ids = implode(',', $projects_ids);
            $query .= 'where  id IN (' . $ids . ') ';
        }
        $query .= 'group By projects.id order by title asc ';
        $projects = \DB::select($query);
        foreach ($projects as $key => $p) {
            $projects[$key] = (array)$p;
        }
        return $projects;
    }

    public static function getAllUserTimesheetProjects($user_id)
    {

        $query = 'SELECT projects.id, uuid, projects.title from projects 
                  join activities on  activities.project_id = projects.id      
                  inner join user_activities on  user_activities.activity_id = activities.id
                  where is_deleted = 0 and active = 1 and activities.deleted_at IS NULL';
        if (session('profile_type') == 3) {
            $query .= 'and user_projects.profile_id = 3';
        }

        $query .= ' and user_activities.user_id = ' . $user_id . ' ';
        $query .= 'group By projects.id order by projects.title asc ';
        $projects = \DB::select($query);
        foreach ($projects as $key => $p) {
            $projects[$key] = (array)$p;
        }
        return $projects;
    }
    public static function getAllUserProjects($user_id)
    {

        $query = 'SELECT projects.id, uuid, projects.title from projects 
                  inner join user_projects on  user_projects.project_id = projects.id
                  join activities on  activities.project_id = projects.id                            
                  where is_deleted = 0 and active = 1 and activities.deleted_at IS NULL';
        if (session('profile_type') == 3) {
            $query .= 'and user_projects.profile_id = 3';
        }

        $query .= ' and user_projects.user_id = ' . $user_id . ' ';
        $query .= 'group By projects.id order by projects.title asc ';
        $projects = \DB::select($query);
        foreach ($projects as $key => $p) {
            $projects[$key] = (array)$p;
        }
        return $projects;
    }

    public static function sizeFormat($bytes, $unit = "", $decimals = 2)
    {
        $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
        $value = 0;
        if ($bytes > 0) {
            if (!array_key_exists($unit, $units)) {
                $pow = floor(log($bytes) / log(1024));
                $unit = array_search($pow, $units);
            }
            $value = ($bytes / pow(1024, floor($units[$unit])));
        }
        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }
        return ['unit' => round($value, 2), 'value' => sprintf('%.' . $decimals . 'f ' . $unit, $value)];
    }

    public static function load_permission_modeul($parent = 0)
    {
        return (new Module())->getSubModuleListForPermission($parent);
    }

    public static function idle_time_calculate_b($user_id = [])
    {
        if (count($user_id) <= 0) {
            $user_id = [492];
        }

        foreach ($user_id as $u_id) {

            $company_user = DB::table('companies_users')->where('user_id', $u_id)->first();

            if ($company_user) {

                $default_approval = $company_user->default_approval == null ? 'A' : ($company_user->default_approval == null ? 'A' : ($company_user->default_approval == 'reject' ? 'd' : 'I'));

                $getTimelogIds = DB::table('timelogs_idle_time_history')
                    ->join('timelogs', 'timelogs_idle_time_history.timelogs_id', '=', 'timelogs.id')
                    ->where('timelogs.user_id', $u_id)
                    ->whereNull('timelogs_idle_time_history.updated_at')
//                    ->where('timelogs.id', 9107)
                    ->select('timelogs_idle_time_history.*', 'timelogs.end_time')
                    ->orderBy('id', 'Desc')
                    ->get();


                foreach ($getTimelogIds as $timeLogId) {

                    $durationTime = $timeLogId->company_setting_idle_time * 60;
                    $tmlg_id = $timeLogId->timelogs_id;
                    while ($durationTime > 0) {
                        $timelog = DB::table('timelogs')
                            ->where('user_id', $u_id)
                            ->where('id', '<', $tmlg_id)
//                            ->where('type', '=', 'A')
                            ->selectRaw("timelogs.id,timelogs.start_time,timelogs.end_time")
                            ->orderBy('id', 'desc')
                            ->first();

                        $timelogDuration = Generic::timeDiffSeconds($timelog->start_time, $timelog->end_time);

                        if ($durationTime >= $timelogDuration) {
                            $durationTime -= $timelogDuration;

                            DB::table('timelogs')->where('id', $timelog->id)->update(['type' => 'I', 'image' => null, 'default_idle_approval' => $default_approval]);

                        } else {

                            $time_left = $timelogDuration - $durationTime;

                            $update_start_time = Carbon::parse($timelog->start_time)
                                ->addSeconds($time_left)
                                ->format('Y-m-d H:i:s');


                            $pre_timelog = DB::table('timelogs')
                                ->where('user_id', $u_id)
                                ->where('id', '<', $timelog->id)
//                                ->where('type', '=', 'A')
                                ->selectRaw("timelogs.id,timelogs.start_time,timelogs.end_time")
                                ->orderBy('id', 'desc')
                                ->first();

                            $pre_update_end_time = Carbon::parse($pre_timelog->end_time)
                                ->addSeconds($time_left)
                                ->format('Y-m-d H:i:s');

                            DB::table('timelogs')->where('id', $timelog->id)->update(['start_time' => $update_start_time, 'type' => 'I', 'default_idle_approval' => $default_approval]);
                            DB::table('timelogs')->where('id', $pre_timelog->id)->update(['end_time' => $pre_update_end_time]);

                            DB::statement('
                            update timelogs as t
                            inner join companies_users as u on u.user_id=t.user_id
                            set t.rate=ifnull(u.rate,0), t.bill= ifnull(u.bill,0 ),
                            t.cost=(TIMESTAMPDIFF(SECOND, start_time, end_time)/3600)*ifnull(u.rate,0),
                            t.bill_cost=(TIMESTAMPDIFF(SECOND, start_time, end_time)/3600)*ifnull(u.bill,0)
                            where t.id=' . $timelog->id . '
                               ');
                            DB::statement('
                            update timelogs as t
                            inner join companies_users as u on u.user_id=t.user_id
                            set t.rate=ifnull(u.rate,0), t.bill= ifnull(u.bill,0 ),
                            t.cost=(TIMESTAMPDIFF(SECOND, start_time, end_time)/3600)*ifnull(u.rate,0),
                            t.bill_cost=(TIMESTAMPDIFF(SECOND, start_time, end_time)/3600)*ifnull(u.bill,0)
                            where t.id=' . $pre_timelog->id . '
                               ');
                            $durationTime = 0;
                        }

                        $tmlg_id = $timelog->id;
                    }
                    DB::table('timelogs_idle_time_history')->where('id', $timeLogId->id)->update(['updated_at' => Carbon::now()]);
                }
            }


        }


    }

    public static function idle_time_calculate($user_id = [],$start_date=null,$end_date=null)
    {
        return 0;
        if (count($user_id) > 0) {
            $user_ids = implode(',', $user_id);
            \DB::select("UPDATE `timelogs` t1
                    JOIN (
                        SELECT `id`, ROW_NUMBER() OVER (PARTITION BY `user_id`, `start_time`, `end_time` ORDER BY `id`) AS row_num
                        FROM `timelogs` USE INDEX (idx_start_end_time)
                        WHERE ( start_time >= '".$start_date."' AND start_time <= '".$end_date."' ) 
                        AND `user_id` IN (" . $user_ids . ") 
                        AND type NOT IN  ('dd','TR') 
                    ) AS duplicates
                    ON t1.`id` = duplicates.`id`
                    SET t1.type = 'dd', t1.deleted = 1
                    WHERE duplicates.row_num > 1;");
        }

        return 0;

//        if (count($user_id) <= 0) {
//            $user_id = [492];
//        }
//
//        foreach ($user_id as $u_id) {
//
//            $company_user = DB::table('companies_users')->where('user_id', $u_id)->first();
//
//            if ($company_user) {
//
//                $default_approval = $company_user->default_approval == null ? 'A' : ($company_user->default_approval == null ? 'A' : ($company_user->default_approval == 'reject' ? 'd' : 'I'));
//
//                $getTimelogIds = DB::table('timelogs_idle_time_history')
//                    ->join('timelogs', 'timelogs_idle_time_history.timelogs_id', '=', 'timelogs.id')
//                    ->where('timelogs.user_id', $u_id)
//                    ->whereNull('timelogs_idle_time_history.updated_at')
////                    ->where('timelogs.id', 9107)
//                    ->select('timelogs_idle_time_history.*', 'timelogs.end_time')
//                    ->orderBy('id', 'Desc')
//                    ->get();
//
//                foreach ($getTimelogIds as $timeLogId) {
//
//                    $durationTime = $timeLogId->company_setting_idle_time * 60;
//                    $tmlg_id = $timeLogId->timelogs_id;
//                    while ($durationTime > 0) {
//                        $timelog = DB::table('timelogs')
//                            ->where('user_id', $u_id)
//                            ->where('id', '<', $tmlg_id)
////                            ->where('type', '=', 'A')
//                            ->selectRaw("timelogs.id,timelogs.start_time,timelogs.end_time")
//                            ->orderBy('id', 'desc')
//                            ->first();
//
//                        if ($timelog) {
//                            $timelogDuration = Generic::timeDiffSeconds($timelog->start_time, $timelog->end_time);
//
//                            if ($durationTime >= $timelogDuration) {
//                                $durationTime -= $timelogDuration;
//
//                                DB::table('timelogs')->where('id', $timelog->id)->update(['type' => 'I', 'image' => null, 'default_idle_approval' => $default_approval]);
//
//                            } else {
//
//                            $time_left=$timelogDuration - $durationTime;
//
//                            $update_start_time = Carbon::parse($timelog->start_time)
//                                ->addSeconds($time_left)
//                                ->format('Y-m-d H:i:s');
//
//
//                                $pre_timelog = DB::table('timelogs')
//                                    ->where('user_id', $u_id)
//                                    ->where('id', '<', $timelog->id)
////                                ->where('type', '=', 'A')
//                                    ->selectRaw("timelogs.id,timelogs.start_time,timelogs.end_time")
//                                    ->orderBy('id', 'desc')
//                                    ->first();
//
//                            $pre_update_end_time=Carbon::parse($pre_timelog->end_time)
//                                ->addSeconds($time_left)
//                                ->format('Y-m-d H:i:s');
//
//                            DB::table('timelogs')->where('id', $timelog->id)->update(['start_time' => $update_start_time,'type'=>'I','default_idle_approval'=>$default_approval]);
//                            DB::table('timelogs')->where('id', $pre_timelog->id)->update(['end_time' => $pre_update_end_time]);
//
//                                DB::statement('
//                            update timelogs as t
//                            inner join companies_users as u on u.user_id=t.user_id
//                            set t.rate=ifnull(u.rate,0), t.bill= ifnull(u.bill,0 ),
//                            t.cost=(TIMESTAMPDIFF(SECOND, start_time, end_time)/3600)*ifnull(u.rate,0),
//                            t.bill_cost=(TIMESTAMPDIFF(SECOND, start_time, end_time)/3600)*ifnull(u.bill,0)
//                            where t.id=' . $timelog->id . '
//                               ');
//                                DB::statement('
//                            update timelogs as t
//                            inner join companies_users as u on u.user_id=t.user_id
//                            set t.rate=ifnull(u.rate,0), t.bill= ifnull(u.bill,0 ),
//                            t.cost=(TIMESTAMPDIFF(SECOND, start_time, end_time)/3600)*ifnull(u.rate,0),
//                            t.bill_cost=(TIMESTAMPDIFF(SECOND, start_time, end_time)/3600)*ifnull(u.bill,0)
//                            where t.id=' . $pre_timelog->id . '
//                               ');
//                                $durationTime = 0;
//                            }
//                            $tmlg_id = $timelog->id;
//                        } else {
//                            $durationTime = 0;
//                        }
//                    }
//                    DB::table('timelogs_idle_time_history')->where('id', $timeLogId->id)->update(['updated_at' => Carbon::now()]);
//                }
//            }
//        }


    }

    public static function getImagePath($path)
    {
        $file_headers = @get_headers($path);
        if (trim($file_headers[0]) == "HTTP/1.1 404") {
            $file = url('img/no-image-found.png');
            print $file;
        } else {
            print $path;
        }
    }

    public static function showLoginUserName()
    {
        $name = session('FirstName');
        return Str::limit($name, 10, $end = '...');
    }

    public static function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    public static function activity_user_logs($type, $module_id, $action_type)
    {
        //type = 1 or 2 , 1 = web and 2 = databse
        $system_info = php_uname();
        $user_id = Auth::id();
        $ip = self::get_client_ip();
        $browser_info = $_SERVER['HTTP_USER_AGENT'];

        $record = [
            "type" => $type,
            "module_id" => $module_id,
            "user_id" => $user_id,
            "ip" => $ip,
            "system_info" => $system_info,
            "browser_info" => $browser_info,
            "action_type" => $action_type
        ];

        \App\Models\ActivityUserLogsModel::create($record);
    }

    public static function calPercentage($wl, $total)
    {
        if ($wl > 0 && $total > 0) {
            return ($wl / $total) * 100;
        }
        return 0;
    }

    public static function hourlyCalculateUserTime()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $timezone = Generic::timeZone();
//        $start_date = Carbon::now()->format('Y-m-d') . ' 00:00:00';
//        $end_date = Carbon::now()->format('Y-m-d') . ' 23:59:59';

//        $start_date = '2023-04-01 00:00:00';
//        $end_date = '2023-04-15 23:59:59';
        $selection = '
                        timelogs.id as t_id,
                      DATE(start_time)  as date,
                      timelogs.user_id as user_id,
                      timelogs.project_id as project_id,
                      timelogs.activity_id as activity_id,
                      timelogs.course_id as course_id,
                      timelogs.user_courses_id as user_courses_id,
                      SUM(CASE 
                             WHEN timelogs.type = "I" AND timelogs.admin_idle_approval IS NOT NULL AND timelogs.admin_idle_approval != "A" AND 
                             ((timelogs.admin_idle_approval = "I") OR (timelogs.admin_idle_approval = "d") )
                             THEN TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time)  
                                 ELSE 
                                 CASE 
                                     WHEN timelogs.type = "I" AND timelogs.default_idle_approval IS NOT NULL AND timelogs.default_idle_approval != "A" AND (timelogs.admin_idle_approval != "A" or timelogs.admin_idle_approval is null) AND ( (timelogs.default_idle_approval = "I") OR (timelogs.default_idle_approval = "d") )
                                       THEN
                                         TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time)  
                                       END
                      END) as idle_seconds,
                      
                       COALESCE(SUM(CASE 
                       WHEN (timelogs.type IN ("A") ) 
                               THEN  TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time) 
                       END),0)
                        +
                       COALESCE(SUM(CASE 
                       WHEN (timelogs.type = "I" AND timelogs.admin_idle_approval IS NOT NULL AND timelogs.admin_idle_approval = "A") 
                           THEN  TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time) 
                       ELSE
                           CASE 
                           WHEN (timelogs.type = "I" AND timelogs.default_idle_approval IS NOT NULL AND timelogs.default_idle_approval = "A" AND (timelogs.admin_idle_approval != "d" or timelogs.admin_idle_approval IS NULL) ) 
                           THEN
                           TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time)  
                           END
                       END),0) 
                        as active_seconds,
                        SUM(CASE WHEN timelogs.type IN ("M")
                        THEN TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time)  
                        END) as manual_seconds,
                        SUM(CASE WHEN timelogs.type IN ("B")
                        THEN TIMESTAMPDIFF(second, timelogs.start_time, timelogs.end_time)  
                        END) as break_seconds,
                        AVG(timelogs.activity_level) as activity_level,
                        SUM(timelogs.bill_cost) as cost,
                        COALESCE(SUM(CASE WHEN timelogs.type IN ("A","M") THEN timelogs.bill_cost END),0) 
                                    +
                                    COALESCE(SUM(CASE 
                                    WHEN (timelogs.type = "I" AND timelogs.admin_idle_approval IS NOT NULL AND timelogs.admin_idle_approval = "A") 
                                       THEN  timelogs.bill_cost
                                    ELSE
                                       CASE 
                                       WHEN (timelogs.type = "I" AND timelogs.default_idle_approval IS NOT NULL AND timelogs.default_idle_approval = "A" AND (timelogs.admin_idle_approval != "d" or timelogs.admin_idle_approval IS NULL)) 
                                       THEN
                                        timelogs.bill_cost
                                       END
                                    END),0) 
                                    as bill
                       
                        ';


//        while (DB::table('timelogs')->where(['summary_log_status'=>0])->whereIn('timelogs.type', ['A','M','I','B'])->exists()){
            $records = DB::table('timelogs')
                ->selectRaw($selection)
                ->from(DB::raw('timelogs USE INDEX (idx_start_end_time)'))
                // ->leftjoin('projects','timelogs.project_id','=','projects.id')
//            ->whereRaw("start_time >= '" . $start_date . "' and end_time <= '" . $end_date . "' ")
                ->where('timelogs.deleted', '=', 0)
                ->whereIn('timelogs.type', ['A','M','I','B'])
                ->where('timelogs.summary_log_status', '=', 0)
                ->groupByRaw('timelogs.id')
                ->limit(10000)
                ->latest()
                ->get()
                ->toArray();

            $user_log = [];
            $user_array_id = [];
            if ( is_array($records) && count($records) > 0 ) {
                foreach ( $records as $r ) {
                    $user_array_id[] = $r->t_id;

                    if ( $r->project_id !== NULL && $r->activity_id !== NULL && $r->course_id == NULL && $r->user_courses_id == NULL  ) {
                        $key = $r->user_id.'_p_'.$r->project_id.'_'.$r->activity_id;
                    } elseif (  $r->project_id == NULL && $r->activity_id == NULL && ( $r->course_id !== NULL && $r->user_courses_id !== NULL && $r->course_id !== 0 && $r->user_courses_id !== 0 ) ) {
                        $key = $r->user_id.'_c_'.$r->course_id.'_'.$r->user_courses_id;
                    } elseif (  $r->project_id == NULL && $r->activity_id == NULL && $r->course_id == 0 && $r->user_courses_id == 0 ) {
                        $key = $r->user_id.'_b_'.$r->course_id.'_'.$r->user_courses_id;
                    }


                    if (!isset($user_log[$key])) {
                        // If the key doesn't exist in mergedData, initialize it
                        $user_log[$key] = [
                            "date" => $r->date,
                            "user_id" => $r->user_id,
                            "project_id" => $r->project_id,
                            "activity_id" => $r->activity_id,
                            "course_id" => $r->course_id,
                            "user_courses_id" => $r->user_courses_id,
                            "active_seconds" =>$r->active_seconds,
                            "idle_seconds" =>$r->idle_seconds ,
                            "break_seconds" =>$r->break_seconds ,
                            "manual_seconds" =>$r->manual_seconds,
                            "activity_level" =>$r->activity_level ?? 0 ,
                            "cost" =>$r->cost ?? 0,
                            "bill" =>$r->bill ?? 0,
                        ];
                    } else {
                        // If the key already exists, add the values to the existing values
                        $user_log[$key]['active_seconds'] += $r->active_seconds;
                        $user_log[$key]['idle_seconds'] += $r->idle_seconds;
                        $user_log[$key]['break_seconds'] += $r->break_seconds;
                        $user_log[$key]['manual_seconds'] += $r->manual_seconds;
                        $user_log[$key]['activity_level'] += $r->activity_level;
                        $user_log[$key]['cost'] += $r->cost;
                        $user_log[$key]['bill'] += $r->bill;
                    }
                }
            }

            if (count($records) > 0) {
                foreach ($records as $r) {
                    // $ids = explode(',', $r->ids);
                    // DB::table('timelogs')->whereIn('id', $ids)->update(['summary_log_status' => 1]);
                    $r = (object)$r;
                    \App\Models\TimelogSummary::updateOrCreate(
                        [
                            "date" => $r->date,
                            "user_id" => $r->user_id,
                            "project_id" => $r->project_id,
                            "activity_id" => $r->activity_id,
                            "course_id" => $r->course_id,
                            "user_courses_id" => $r->user_courses_id,
                        ],
                        [
                            "date" => $r->date,
                            "user_id" => $r->user_id,
                            "project_id" => $r->project_id,
                            "activity_id" => $r->activity_id,
                            "course_id" => $r->course_id,
                            "user_courses_id" => $r->user_courses_id,
                            "active_seconds" =>$r->active_seconds !=null ? \DB::raw("COALESCE(active_seconds, 0) + " . $r->active_seconds ?? 0) : \DB::raw("COALESCE(active_seconds, 0) + " .  0),
                            "idle_seconds" =>$r->idle_seconds !=null ? \DB::raw("COALESCE(idle_seconds, 0) + " . $r->idle_seconds ?? 0) : \DB::raw("COALESCE(idle_seconds, 0) + " .  0),
                            "break_seconds" =>$r->break_seconds !=null ? \DB::raw("COALESCE(break_seconds, 0) + " . $r->break_seconds ?? 0) : \DB::raw("COALESCE(break_seconds, 0) + " .  0),
                            "manual_seconds" =>$r->manual_seconds !=null ? \DB::raw("COALESCE(manual_seconds, 0) + " . $r->manual_seconds ?? 0) : \DB::raw("COALESCE(manual_seconds, 0) + " . 0),
                            "activity_level" =>$r->activity_level !=null ? \DB::raw("COALESCE(activity_level, 0) + " . $r->activity_level ?? 0) : \DB::raw("COALESCE(activity_level, 0) + " . 0),
                            "cost" =>$r->cost !=null ? \DB::raw("COALESCE(cost, 0) + " . $r->cost ?? 0) : \DB::raw("COALESCE(cost, 0) + " . 0),
                            "bill" =>$r->bill !=null ? \DB::raw("COALESCE(bill, 0) + " . $r->bill ?? 0) : \DB::raw("COALESCE(bill, 0) + " . 0),

                        ]
                    );

                }
            }
            $chunkSize = 100; // Adjust the chunk size as needed
            $userArrayChunks = array_chunk($user_array_id, $chunkSize);

            foreach ($userArrayChunks as $chunk) {
                DB::table('timelogs')
                    ->whereIn('id', $chunk)
                    ->update(['summary_log_status' => 1]);
            }
//        }



        return true;

    }
    public static function updateUserTimeAfterAddedManualTime($date,$userId,$flag=true)
    {
        return 0;
        ini_set('memory_limit', '-1');

        $timezone = Generic::timeZone();
        $start_date = Carbon::parse($date)->format('Y-m-d') . ' 00:00:00';
        $end_date = Carbon::parse($date)->format('Y-m-d') . ' 23:59:59';
        $date_val = Carbon::parse($date)->format('Y-m-d');

        $selection = '
                        timelogs.id as t_id,
                      DATE(start_time)  as date,
                      timelogs.user_id as user_id,
                      timelogs.project_id as project_id,
                      timelogs.activity_id as activity_id,
                      timelogs.course_id as course_id,
                      timelogs.user_courses_id as user_courses_id,
                      SUM(CASE 
                             WHEN timelogs.type = "I" AND timelogs.admin_idle_approval IS NOT NULL AND timelogs.admin_idle_approval != "A" AND 
                             ((timelogs.admin_idle_approval = "I") OR (timelogs.admin_idle_approval = "d") )
                             THEN TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time)  
                                 ELSE 
                                 CASE 
                                     WHEN timelogs.type = "I" AND timelogs.default_idle_approval IS NOT NULL AND timelogs.default_idle_approval != "A" AND (timelogs.admin_idle_approval != "A" or timelogs.admin_idle_approval is null) AND ( (timelogs.default_idle_approval = "I") OR (timelogs.default_idle_approval = "d") )
                                       THEN
                                         TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time)  
                                       END
                      END) as idle_seconds,
                      
                       COALESCE(SUM(CASE 
                       WHEN (timelogs.type IN ("A") ) 
                               THEN  TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time) 
                       END),0)
                        +
                       COALESCE(SUM(CASE 
                       WHEN (timelogs.type = "I" AND timelogs.admin_idle_approval IS NOT NULL AND timelogs.admin_idle_approval = "A") 
                           THEN  TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time) 
                       ELSE
                           CASE 
                           WHEN (timelogs.type = "I" AND timelogs.default_idle_approval IS NOT NULL AND timelogs.default_idle_approval = "A" AND (timelogs.admin_idle_approval != "d" or timelogs.admin_idle_approval IS NULL) ) 
                           THEN
                           TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time)  
                           END
                       END),0) 
                        as active_seconds,
                        SUM(CASE WHEN timelogs.type IN ("M")
                        THEN TIMESTAMPDIFF(second,timelogs.start_time,timelogs.end_time)  
                        END) as manual_seconds,
                        SUM(CASE WHEN timelogs.type IN ("B")
                        THEN TIMESTAMPDIFF(second, timelogs.start_time, timelogs.end_time)  
                        END) as break_seconds,
                        AVG(timelogs.activity_level) as activity_level,
                        SUM(timelogs.bill_cost) as cost,
                        COALESCE(SUM(CASE WHEN timelogs.type IN ("A","M") THEN timelogs.bill_cost END),0) 
                                    +
                                    COALESCE(SUM(CASE 
                                    WHEN (timelogs.type = "I" AND timelogs.admin_idle_approval IS NOT NULL AND timelogs.admin_idle_approval = "A") 
                                       THEN  timelogs.bill_cost
                                    ELSE
                                       CASE 
                                       WHEN (timelogs.type = "I" AND timelogs.default_idle_approval IS NOT NULL AND timelogs.default_idle_approval = "A" AND (timelogs.admin_idle_approval != "d" or timelogs.admin_idle_approval IS NULL)) 
                                       THEN
                                        timelogs.bill_cost
                                       END
                                    END),0) 
                                    as bill
                       
                        ';
        while (DB::table('timelogs')->where(['summary_log_status'=>0])->whereIn('timelogs.type', ['A','M','I','B'])->exists()) {

            $records = DB::table('timelogs')
                ->selectRaw($selection)
                ->from(DB::raw('timelogs USE INDEX (idx_start_end_time)'))
                ->whereRaw("timelogs.start_time >= convert_tz('" . $start_date . "','" . $timezone . "','utc') and timelogs.start_time <= convert_tz('" . $end_date . "','" . $timezone . "','utc') ")
                ->where('timelogs.deleted', '=', 0)
                ->whereIn('timelogs.type', ['A', 'M', 'I', 'B'])
//            ->where('timelogs.summary_log_status', '=', 0)
                ->groupByRaw('timelogs.id')
                ->where('timelogs.user_id', $userId)
//            ->where('summary_log_status', '=', 0)
                ->limit(5000)

                ->groupByRaw('timelogs.id')
                ->get()->toArray();

            if ($flag == true) {
                \App\Models\TimelogSummary::where(['date' => $date_val, 'user_id' => $userId])->delete();
            }
            $user_log = [];
            $user_array_id = [];
            if (is_array($records) && count($records) > 0) {
                foreach ($records as $r) {
                    $user_array_id[] = $r->t_id;

                    if ($r->project_id !== NULL && $r->activity_id !== NULL && $r->course_id == NULL && $r->user_courses_id == NULL) {
                        $key = $r->user_id . '_p_' . $r->project_id . '_' . $r->activity_id;
                    } elseif ($r->project_id == NULL && $r->activity_id == NULL && ($r->course_id !== NULL && $r->user_courses_id !== NULL && $r->course_id !== 0 && $r->user_courses_id !== 0)) {
                        $key = $r->user_id . '_c_' . $r->course_id . '_' . $r->user_courses_id;
                    } elseif ($r->project_id == NULL && $r->activity_id == NULL && $r->course_id == 0 && $r->user_courses_id == 0) {
                        $key = $r->user_id . '_b_' . $r->course_id . '_' . $r->user_courses_id;
                    }


                    if (!isset($user_log[$key])) {
                        // If the key doesn't exist in mergedData, initialize it
                        $user_log[$key] = [
                            "date" => $r->date,
                            "user_id" => $r->user_id,
                            "project_id" => $r->project_id,
                            "activity_id" => $r->activity_id,
                            "course_id" => $r->course_id,
                            "user_courses_id" => $r->user_courses_id,
                            "active_seconds" => $r->active_seconds,
                            "idle_seconds" => $r->idle_seconds,
                            "break_seconds" => $r->break_seconds,
                            "manual_seconds" => $r->manual_seconds,
                            "activity_level" => $r->activity_level ?? 0,
                            "cost" => $r->cost ?? 0,
                            "bill" => $r->bill ?? 0,
                        ];
                    } else {
                        // If the key already exists, add the values to the existing values
                        $user_log[$key]['active_seconds'] += $r->active_seconds;
                        $user_log[$key]['idle_seconds'] += $r->idle_seconds;
                        $user_log[$key]['break_seconds'] += $r->break_seconds;
                        $user_log[$key]['manual_seconds'] += $r->manual_seconds;
                        $user_log[$key]['activity_level'] += $r->activity_level;
                        $user_log[$key]['cost'] += $r->cost;
                        $user_log[$key]['bill'] += $r->bill;
                    }
                }
            }

            if (count($records) > 0) {
                foreach ($records as $r) {
                    // $ids = explode(',', $r->ids);
                    // DB::table('timelogs')->whereIn('id', $ids)->update(['summary_log_status' => 1]);
                    $r = (object)$r;
                    \App\Models\TimelogSummary::updateOrCreate(
                        [
                            "date" => $r->date,
                            "user_id" => $r->user_id,
                            "project_id" => $r->project_id,
                            "activity_id" => $r->activity_id,
                            "course_id" => $r->course_id,
                            "user_courses_id" => $r->user_courses_id,
                        ],
                        [
                            "date" => $r->date,
                            "user_id" => $r->user_id,
                            "project_id" => $r->project_id,
                            "activity_id" => $r->activity_id,
                            "course_id" => $r->course_id,
                            "user_courses_id" => $r->user_courses_id,
                            "active_seconds" => $r->active_seconds != null ? \DB::raw("COALESCE(active_seconds, 0) + " . $r->active_seconds ?? 0) : \DB::raw("COALESCE(active_seconds, 0) + " . 0),
                            "idle_seconds" => $r->idle_seconds != null ? \DB::raw("COALESCE(idle_seconds, 0) + " . $r->idle_seconds ?? 0) : \DB::raw("COALESCE(idle_seconds, 0) + " . 0),
                            "break_seconds" => $r->break_seconds != null ? \DB::raw("COALESCE(break_seconds, 0) + " . $r->break_seconds ?? 0) : \DB::raw("COALESCE(break_seconds, 0) + " . 0),
                            "manual_seconds" => $r->manual_seconds != null ? \DB::raw("COALESCE(manual_seconds, 0) + " . $r->manual_seconds ?? 0) : \DB::raw("COALESCE(manual_seconds, 0) + " . 0),
                            "activity_level" => $r->activity_level != null ? \DB::raw("COALESCE(activity_level, 0) + " . $r->activity_level ?? 0) : \DB::raw("COALESCE(activity_level, 0) + " . 0),
                            "cost" => $r->cost != null ? \DB::raw("COALESCE(cost, 0) + " . $r->cost ?? 0) : \DB::raw("COALESCE(cost, 0) + " . 0),
                            "bill" => $r->bill != null ? \DB::raw("COALESCE(bill, 0) + " . $r->bill ?? 0) : \DB::raw("COALESCE(bill, 0) + " . 0),

                        ]
                    );

                }
            }

            $chunkSize = 100; // Adjust the chunk size as needed
            $userArrayChunks = array_chunk($user_array_id, $chunkSize);

            foreach ($userArrayChunks as $chunk) {
                DB::table('timelogs')
                    ->whereIn('id', $chunk)
                    ->update(['summary_log_status' => 1]);
            }
        }

        return true;

    }

    public static function get_graph_color($status='completed')
    {
        switch(session('theme_color'))
        {
            case 'theme-two':
                    if ( $status == 'completed' ) {
                        return 'rgba(30, 148, 253, 1)';
                    } elseif ( $status == 'pending' ) {
                        return 'rgba(234, 94, 133, 1)';
                    }
                break;

            case 'theme-one':
                    if ( $status == 'completed' ) {
                        return 'rgba(53, 164, 118, 1)';
                    } elseif ( $status == 'pending' ) {
                        return 'rgba(234, 94, 133, 1)';
                    }
                break;


            case 'theme-three':
                    if ( $status == 'completed' ) {
                        return 'rgba(31, 84, 138, 1)';
                    } elseif ( $status == 'pending' ) {
                        return 'rgba(234, 94, 133, 1)';
                    }
                break;


            case 'theme-four':
                    if ( $status == 'completed' ) {
                        return 'rgba(23, 143, 219, 1)';
                    } elseif ( $status == 'pending' ) {
                        return 'rgba(234, 94, 133, 1)';
                    }
                break;

            default: 

                break;

        }
    }

    public static function get_dashboard_svg_color($status='completed')
    {
        switch(session('theme_color'))
        {
            case 'theme-two':
                    return $status.'-two.svg';
                break;
            case 'theme-one':
                    return $status.'-one.svg';
                break;
            case 'theme-three':
                    return $status.'-three.svg';
                break;
            case 'theme-four':
                    return $status.'-four.svg';
                break;

            default: 

                break;
        }
    }

    public static function theme_color()
    {
        switch(session('theme_color'))
        {
            case 'theme-two':
                return "#2892FD";
                break;
            case 'theme-one':
                return "#35A476";
                break;
            case 'theme-three':
                return "#1F548A";
                break;
            case 'theme-four':
                return "#2D3064";
                break;

            default:

                break;
        }
    }

    public function user_blocked_notification($company_id, $user_ids)
    {
        $timezone = Generic::timeZone($company_id);
        $dateNow = Carbon::now();
        $dateNow->setTimezone($timezone);
        $timezone = $dateNow->format('Y-m-d H:i:s.u');

        $title ='Your StaffViz access has been revoked. Please reach out to your company admin for more information.';
        $event_code = 'user_blocked';
        foreach ($user_ids as $user_id) {
            $notification = [
                'user_id' => $user_id,
                'auth_key' => 1,
                'message_type' => 1,
                'view_option' => 1,
                'view_hide_date' => 1,
                'view_profile' => 1,
                'read_status' => 1,
                'source_type' => 1,
                'target_app' => 1,
                'created_at' => $timezone,
                'updated_at' => $timezone,
            ];
            $push_notification_id = DB::table('push_notifications')->insertGetId($notification);
            $data['companyId'] = $company_id;
            $message_body = [
                'event_code' => $event_code,
                'id' => $user_id,
                'title' => $title,
                'push_notification_id' => $push_notification_id,
                'description' => '',
                'notification_date_time' => $timezone,
            ];
            $data['payload'] = ['audience' => 'user', 'message' => $message_body, 'userId' => [$user_id]];
            $data_up = json_encode($data);
            DB::table('push_notifications')->where('id', $push_notification_id)->update(['message' => $data_up]);
            
        }
        self::send_data_to_kafka($company_id, $user_ids);
    }
    public static function send_data_to_kafka($company_id,$users=[])
    {
        $end_point = getenv('KAFKA_NOTIFICATION_API');
        $auth_key = getenv('KAFKA_NOTIFICATION_AUTH_KEY');
        $fields = [
            'companyId' => $company_id,
            'userIds' => $users, 
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $end_point,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                'AUTH_KEY: ' . $auth_key . '',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        return $response;
    }


    public function user_blocked_notificationOLD($company_id, $user_ids)
    {
        $timezone = Generic::timeZone($company_id);
        $dateNow = Carbon::now();
        $dateNow->setTimezone($timezone);
        $timezone = $dateNow->format('Y-m-d H:i:s.u');
        $users = DB::table('users')
            ->select('users.id')
            ->join('companies_users','companies_users.user_id','=','users.id')
            ->wherein('companies_users.user_id',$user_ids)
            ->get()
            ->toArray();
        foreach ($users as $user) {
            $users_ids = [];
            $users_ids[] = $user->id;
            $event_code = 'user_blocked';
            $id = $user->id;
            $title ='You have been blocked. Please contact your admin.';
            $message = '';
            $params = '';

            Generic::sendNotification($users_ids, $event_code, $title, $params,$users_ids);
        }
    }

    public static function sendNotificationOLD($user_ids, $event_code, $title, $params,$project_users)
    {
        $timezone = Generic::timeZone();
        $dateNow = \Carbon\Carbon::now();
        $dateNow->setTimezone($timezone);
        $timezone = $dateNow->format('Y-m-d H:i:s.u');
        foreach ($user_ids as $ids) {
            $notification = [
                'user_id' => $ids,
                'auth_key' => 1,
                'message_type' => 1,
                'view_option' => 1,
                'view_hide_date' => 1,
                'view_profile' => 1,
                'read_status' => 1,
                'source_type' => 1,
                'target_app' => 1,
                'created_at' => $timezone,
                'updated_at' => $timezone,
            ];
            $push_notification_id = DB::table('push_notifications')->insertGetId($notification);
            $data['companyId'] = session('company_id');
            $message_body = [
                'event_code' => $event_code,
                'id' => $ids,
                'title' => $title,
                'push_notification_id' => $push_notification_id,
                'description' => $params,
                'notification_date_time' => $timezone,
            ];
            $data['payload'] = ['audience' => 'user', 'message' => $message_body, 'userId' => [$ids]];
            $data_up = json_encode($data);
            DB::table('push_notifications')->where('id', $push_notification_id)->update(['message' => $data_up]);
        }
        self::send_data_to_kafka(session('company_id'), $project_users);
    }
    public static function send_data_to_kafkaOLD($company_id,$users=[])
    {
        $end_point = getenv('KAFKA_NOTIFICATION_API');
        $auth_key = getenv('KAFKA_NOTIFICATION_AUTH_KEY');
        $fields = [
                    'companyId' => $company_id,
                    'userIds' => $users, 
                ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $end_point,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                'AUTH_KEY: ' . $auth_key . '',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        return $response;
    }

}


