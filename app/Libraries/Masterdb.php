<?php

namespace App\Libraries;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
/**
 *
 */
class Masterdb
{

    public static function connect_master_db()
    {
        config(["database.connections.mysql" => [
                "driver" => "mysql",
                "host" => env("DB_HOST"),
                "port" => env("DB_PORT"),
                "database" => env("DB_DATABASE"),
                "username" => env("DB_USERNAME"),
                "password" => env("DB_PASSWORD"),
                "charset" => "utf8",
                "collation" => "utf8_unicode_ci",
                "prefix" => "typicms_",
                "strict" => false,
                "engine" => null
            ]]
        );
        Config::set("database.default", "mysql");
        DB::reconnect();
        return true;
    }

    public static function create_company_db_on_master_db($compane_id)
    {
        config(['database.connections.company_' . $compane_id => ["driver" => "mysql",
                "host" => env("DB_HOST"),
                "port" => env("DB_PORT"),
                "database" => env("DB_DATABASE"),
                "username" => env("DB_USERNAME"),
                "password" => env("DB_PASSWORD"),
                "charset" => "utf8",
                "collation" => "utf8_unicode_ci",
                "prefix" => "",
                "strict" => false,
                "engine" => null
            ]]
        );
        return true;
    }

    public static function connect_master_db_param($company_initial,$db_host,$db_port,$db_username,$db_password)
    {
        config(["database.connections." . $company_initial => [
                "driver" => 'mysql',
                "host" => !empty($db_host) ? $db_host : '',
                "port" => !empty($db_port) ? $db_port : '',
                "database" => $company_initial,
                "username" => !empty($db_username) ? $db_username : '',
                "password" => !empty($db_password) ? $db_password : '',
                "charset" => "utf8",
                "collation" => "utf8_unicode_ci",
                "prefix" => "typicms_",
                "strict" => false,
                "engine" => null
            ]]
        );
        return true;
    }

    public static function connect_company_db_param($company_initial,$db_host,$db_port,$db_username,$db_password,$db_driver=null)
    {
        $table_prefix = "";
        if ( $db_driver == 'mysql' ) {
            //$table_prefix = "typicms_"; 
        }
        config(["database.connections." . $company_initial => [
                "driver" => !empty($db_driver) ? $db_driver : 'mysql',
                "host" => !empty($db_host) ? $db_host : '',
                "port" => !empty($db_port) ? $db_port : '',
                "database" => $company_initial,
                "username" => !empty($db_username) ? $db_username : '',
                "password" => !empty($db_password) ? $db_password : '',
                "charset" => "utf8",
                "collation" => "utf8_unicode_ci",
                "prefix" => $table_prefix,
                "strict" => false,
                "engine" => null
            ]]
        );
        return true;
    }
    public static function connect_company_db($company_initial)
    {
        $exists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$company_initial]);
        if (count($exists) > 0) {
            config(["database.connections." . $company_initial => [
                    "driver" => getenv('DB_CONNECTION'),
                    "host" =>  getenv('DB_HOST'),
                    "port" => getenv('DB_PORT'),
                    "database" => $company_initial,
                    "username" => getenv('DB_USERNAME'),
                    "password" => getenv('DB_PASSWORD'),
                    "charset" => "utf8",
                    "collation" => "utf8_unicode_ci",
                    "prefix" => "",
                    "strict" => false,
                    "engine" => null
                ]
            ]);
            Config::set("database.default", $company_initial);
            DB::reconnect();
            return true;
        }
        return false;
    }
}


