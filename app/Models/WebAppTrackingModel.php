<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Libraries\Masterdb;
use App\Models\WebAppTrackingSummariezModel;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class WebAppTrackingModel extends Model
{
    use HasFactory;

    protected $table = 'web_app_tracking';

    public function get_data($company_initial=null)
    {
        return $this->get_web_app_tracking_data($company_initial);
    }

    private function get_web_app_tracking_data($company_id)
    {
        set_time_limit(0);
        $company_db = Masterdb::connect_company_db($company_id);
        if ( $company_db == false ) {
            return true;
        }
        $timezone = $this->getCompanyTimeZone();
        $delete_id = [];
        $result = [];
        // Step 1: Disable `ONLY_FULL_GROUP_BY` mode
        \DB::statement("SET sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        \DB::statement("SET SESSION group_concat_max_len = 200000");
        $db = \DB::table('web_app_tracking')
                ->select(\DB::raw("user_id, app,description, DATE(CONVERT_TZ(start_time, '".$timezone."', 'UTC')) AS start_date, SUM(spend_time) AS total_spend_time, url, GROUP_CONCAT( `web_app_tracking`.`id` ORDER BY `web_app_tracking`.`id` ASC ) AS ids,web_app_tracking.type as w_type"))
                ->join('users','users.id','=','web_app_tracking.user_id')
                ->where('web_app_tracking.status','=',1)
                // ->where('user_id',2148)
                // ->groupByRaw('start_date, app, user_id')
                ->groupByRaw('user_id, app, description, start_date, url, w_type')
                ->orderBy('start_date')
                ->get()
                ->toArray();
                // ->toRawSql();
        // dd($db);
        if ( is_array($db) && count($db) > 0 ) {
            foreach ( $db as $db_index => $db_row ) {
                $type = $db_row->w_type;//in_array(trim($db_row->app), $this->get_website_list()) ? 1 : 2;
                $url_count = explode(',',$db_row->ids);
                $cleaned_string = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{2300}-\x{23FF}\x{2B50}]+/u', '', $db_row->description);
                if ( $type == 2 ) {
                    $description = $cleaned_string;
                    $domain_name = null;
                } else {
                    $description = $this->add_https_url($db_row->url);
                    $domain_name = $this->get_domain($db_row->url);
                }
                $result[] = [
                                'id' => Str::uuid()->toString(),
                                'app_name' => $db_row->app,
                                'user_id' => $db_row->user_id,
                                'type' => $type,
                                'date_entry' => $db_row->start_date,
                                'spent_time' => round($db_row->total_spend_time),
                                'domain_name' => $domain_name,
                                'full_url' => $description,
                                'url_count' => count($url_count),
                                'timezone' => $timezone,
                                'created_at' => date('Y-m-d H:i:s'),
                            ];
                $delete_id[] = $db_row->ids;

            }
            // print '<pre>';
            // print_r($result);
            // print '</pre>';
            // die();
            $db_insert_array = [];
            if ( is_array($result) && count($result) > 0 ) {
                foreach($result as $index => $row) {
                    if ( $row['type'] == 2 ) {
                        $domain_users = $this->get_domain_app_id($row['app_name'],$row['user_id']);

                        $result[$index]['domain_validation'] = 2;
                        $result[$index]['productivity'] = 1;
                        if ( array_key_exists($row['user_id'], $domain_users) ) {
                            $result[$index]['productivity'] = $domain_users[$row['user_id']];
                        }
                        // dd($result);
                        $db_insert_array[] = $result[$index];
                        // dd($domain_users,$db_insert_array);
                    } else {
                        if ( $row['domain_name'] !== null ) {
                            $domain_users = $this->get_domain_id($row['domain_name'],$row['domain_name'],null,$row['user_id']);
                            $domain_validation = $this->isValidDomain($row['domain_name']);
                            $result[$index] = $row;
                            $result[$index]['productivity'] = 1;
                            if ( array_key_exists($row['user_id'], $domain_users) ) {
                                $result[$index]['productivity'] = $domain_users[$row['user_id']];
                            }
                                $result[$index]['domain_validation'] = $domain_validation;
                            //$index++;
                            $db_insert_array[] = $result[$index];
                        } else {
                            $result[$index]['productivity'] = 1;
                            $result[$index]['domain_validation'] = 2;
                            $db_insert_array[] = $result[$index];
                        }
                    }
                }
            }

            // print '<pre>';
            // print_r($result);
            // print '</pre>';

            // print '<pre>';
            // print_r($db_insert_array);
            // print '</pre>';

            // die();
            // dd($db_insert_array);
            WebAppTrackingSummariezModel::update_or_insert($db_insert_array);
            $this->delete_rows($delete_id);
            return true;
        }
    }

    private function insert_web_data($data=[],$company_id=0)
    {
        $insert_data = [];
        if ( is_array($data) && count($data) > 0 ) {
            $index = 0;
            foreach( $data as $final_app ) {
                if ( $final_app['domain_name'] !== null && $final_app['full_url'] !== '/' ) {
                    $domain_users = $this->get_domain_id($final_app['domain_name'],$final_app['domain_name'],null,$company_id);
                    $domain_validation = $this->isValidDomain($final_app['domain_name']);
                    $insert_data[$index] = $final_app;
                    if ( array_key_exists($final_app['user_id'], $domain_users) ) {
                        $insert_data[$index]['productivity'] = $domain_users[$final_app['user_id']];
                    }
                        $insert_data[$index]['domain_validation'] = $domain_validation;
                    $index++;
                } else {
                    $app_logo = null;
                }
            }
        }
        return $insert_data;
    }

    private function getImageName($url,$company_id)
    {
        if (!preg_match("/^https?:\/\//i", $url)) {
            // Add http:// if no protocol is present
            $url = "http://" . $url;
        }
        $imageUrl = $this->getFaviconUrl($url);
        if ( $imageUrl == null ) {
            return null;
        }
        try {
            $ch = curl_init($imageUrl);
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as a string
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);  // Timeout in seconds
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  // Connection timeout in seconds
            $imageContents = curl_exec($ch);
            curl_close($ch);
            if ($imageContents === false) {
                return null;
            }
            // Optionally, check the content type to verify it is an image
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $imageContents);
            finfo_close($finfo);
            if (strpos($mimeType, 'image') === false) {
                return null;
            }
            $allowed_ext = ['jpg','jpeg', 'png', 'gif', 'bmp','webp'];
            $mimeType = File::extension($mimeType);
            if ( in_array(strtolower($mimeType),$allowed_ext) ) {
                $filename = time() . '.'.$mimeType;
                $path = 'tth/'.$company_id.'/webapp/' . $filename;
                Storage::put( $path, $imageContents );
                $temporaryUrl = storage_path('app/' . $path);
                $image = Image::make($temporaryUrl);
                $image->resize(50, 50);
                $finalFileName = 'tth/'.$company_id.'/webapp/' . 'image_' . time() . '.jpg';
                Storage::disk('s3')->put($finalFileName, (string) $image->encode(), 'public');
                Storage::delete($temporaryUrl);
                return $finalFileName;
            } else {
                $finalFileName = 'tth/'.$company_id.'/webapp/' . 'image_' . time() . '.'.$mimeType;
                Storage::disk('s3')->put($finalFileName, (string) base64_encode($imageContents), 'public');
                return $finalFileName;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function getCompanyTimeZone()
    {
        $db = \DB::table('companies')->first();
        if ( !empty($db) ) {
            return $db->timezone_id;
        }
        return NULL;
    }

    private function delete_rows($ids)
    {
        // dd($ids);
        // $delid = explode(',',$ids);
        // $quotedArray = array_map(function($item) {
        //     return "'" . trim($item) . "'"; // Add single quotes around each element
        // }, $delid);
        //$sql = "DELETE FROM `web_app_tracking` WHERE `id` COLLATE utf8mb4_0900_ai_ci IN (" . implode(',', $quotedArray) . ")";
        $sql = "UPDATE `web_app_tracking` set status=2 ";
        \DB::statement($sql);
        return true;
    }

    private function get_website_list()
    {
        $result = [];
        $sql = \DB::table('web_app_tracking_meta')->where('type',1)->get()->toArray();
        if ( is_array($sql) && count($sql) > 0 ) {
            foreach( $sql as $q ) {
                $result[] = $q->app;
            }
        }
        return $result;
    }

    private function get_domain_app_id($domain,$company_id=0)
    {
        $domain_id = 0;
        $get = \DB::table('web_app_tracking_domain')
                    ->select('web_app_tracking_domain.id as id')
                    ->join('web_app_tracking_domain_users','web_app_tracking_domain_users.domain_id','=','web_app_tracking_domain.id')
                    ->where('web_app_tracking_domain.type',2)
                    ->where('web_app_tracking_domain.domain_name',$domain)
                    ->where('web_app_tracking_domain_users.user_id',$company_id)
                    ->first();
        if ( $get ) {
            $domain_id = $get->id;
        } else {
            // $sql = \DB::table('web_app_tracking_domain')->insertGetId([
            //     'domain_name' => $domain,
            //     'type' => 2,
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s')
            // ]);
            // $domain_id = $sql;
            // $this->insert_domain_users($domain_id);
        }
        return $this->get_company_users_id_domain($domain_id,$company_id);
    }

    private function get_domain_id($domain,$domain_url,$app_logo=null,$company_id=0)
    {
        $domain_name = ( null !== $this->getDomainUrl($domain) ) ? $this->getDomainUrl($domain) : $domain;
        $domain_id = 0;
        $get = \DB::table('web_app_tracking_domain')
                    ->select('web_app_tracking_domain_users.domain_id as domain_id')
                    ->join('web_app_tracking_domain_users','web_app_tracking_domain_users.domain_id','=','web_app_tracking_domain.id')
                    ->where('web_app_tracking_domain.type',1)
                    ->where('web_app_tracking_domain.domain_url',$domain)
                    ->where('web_app_tracking_domain_users.user_id',$company_id)
                    ->first();
        if ( $get ) {
            $domain_id = $get->domain_id;
        } else {
            $domain_validation = $this->isValidDomain($domain);
            // if ( $domain_validation == 1 ) {
            //     $app_logos = $this->getImageName($domain,$company_id);
            // } else {
                $app_logos = null;
            // }
            // $sql = \DB::table('web_app_tracking_domain')->insertGetId([
            //             'domain_name' => $domain_name,
            //             'domain_url' => $domain,
            //             'app_logo' => $app_logos,
            //             'domain_validation' => $domain_validation,
            //             'type' => 1,
            //             'created_at' => date('Y-m-d H:i:s'),
            //             'updated_at' => date('Y-m-d H:i:s'),
            //         ]);
            // $domain_id = $sql;
            // $this->insert_domain_users($domain_id);
        }
        return $this->get_company_users_id_domain($domain_id,$company_id);
    }

    private function get_company_users()
    {
        return \DB::table('users')->get();
    }

    private function insert_domain_users($domain_id)
    {
        $users = $this->get_company_users();
        $insert = [];
        if ( is_object($users) && count($users) > 0 ) {
            foreach( $users as $u ) {
                if ( empty($this->get_domain_exists_or_not($u->id,$domain_id) ) ) {
                    $insert[] = [
                                    'user_id' => $u->id,
                                    'domain_id' => $domain_id,
                                    'productivity' => 1,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ];
                }
            }
        }
        if ( is_array($insert) && count($insert) > 0 ) {
            return \DB::table('web_app_tracking_domain_users')->insert($insert);
        }
        return false;
    }

    private function get_domain_exists_or_not($user_id,$domain_id)
    {
        return \DB::table('web_app_tracking_domain_users')
                    ->where('user_id',$user_id)
                    ->where('domain_id',$domain_id)
                    ->first();
    }

    private function get_company_users_id_domain($domain_id,$user_id=0)
    {
        $r = [];
        $users = \DB::table('users')->where('id',$user_id)->limit(1)->get()->toArray()[0];
        if ( $users->productivity == 1 ) {
            $sql = \DB::table('web_app_tracking_domain_users')->where('domain_id', $domain_id)->get();
            if ( is_object($sql) && count($sql) > 0 ) {
                foreach( $sql as $q  ) {
                    $r[$q->user_id] = $q->productivity;
                }
            }
        } else {
           $sql = \DB::table('web_app_tracking_domain')->where('id', $domain_id)->get();
            if ( is_object($sql) && count($sql) > 0 ) {
                foreach( $sql as $q  ) {
                    $r[$user_id] = $q->productivity;
                }
            }
        }
        // dd($users,$user_id,$domain_id);
        // $sql = \DB::table('web_app_tracking_domain_users')->where('domain_id', $domain_id)->get();
        // if ( is_object($sql) && count($sql) > 0 ) {
        //     foreach( $sql as $q  ) {
        //         $r[$q->user_id] = $q->productivity;
        //     }
        // }
        return $r;
    }

    private function getDomainUrl($url)
    {
        if (!preg_match('/^http[s]?:\/\//', $url)) {
            $url = "http://" . $url;
        }
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['host'])) {
            // Remove 'www.' prefix from domain name if it exists
            return preg_replace('/^www\./', '', $parsedUrl['host']);
        }
        return null;
    }

    private function getFaviconUrl($url)
    {
        try {
            // Fetch the content of the page using HTTP client
            $response = Http::timeout(30)->connectTimeout(10)->retry(1, 200)->get($url);
            if ($response->successful()) {
                // Initialize the crawler with the HTML content
                $crawler = new Crawler($response->body());
                // Find the <link rel="icon"> or <link rel="shortcut icon">
                $favicon = $crawler->filter('link[rel="icon"], link[rel="shortcut icon"]')->first();
                // Get the href attribute (the URL of the favicon)
                $faviconUrl = $favicon->attr('href');
                // If the favicon URL is relative, we need to make it absolute
                if (!filter_var($faviconUrl, FILTER_VALIDATE_URL)) {
                    // Combine the base URL with the relative favicon path
                    $faviconUrl = rtrim($url, '/') . '/' . ltrim($faviconUrl, '/');
                }
                return $faviconUrl;
            }
            return null;  // Return null if the page fetch failed
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., connection failures, timeouts)
            return $e->getMessage();
        }
    }

    private function isValidDomain($domain) {
        // Check if the domain has a valid format
        if (!filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            return 2;
        }
        // Check if the domain has DNS records
        if (!checkdnsrr($domain, "ANY")) {
            return 2;
        }
        // Domain is valid and has DNS records
        return 1;
    }

    private function get_domain($url)
    {
        $filter_domain = ['chrome://newtab/'];
        // Ensure the URL has a protocol (http://)
        if (!preg_match('/^http[s]?:\/\//', $url)) {
            $url = "http://" . $url;
        }
        // Parse the URL
        $parsedUrl = parse_url($url);
        // Check if parse_url returned false or if the 'host' key exists
        if ($parsedUrl === false || !isset($parsedUrl['host'])) {
            return 'https://www.google.com';
        }
        // Return the domain (host)
        return preg_replace('/^www\./', '', $parsedUrl['host']);
    }

    private function add_https_url($url)
    {
        // Ensure the URL has a protocol (http://)
        if (!preg_match('/^http[s]?:\/\//', $url)) {
            $url = "http://" . $url;
        }
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return 'https://www.google.com/search?q='.$url;
        }

        return $url;
    }

}
