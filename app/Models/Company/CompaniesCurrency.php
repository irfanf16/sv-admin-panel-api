<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Libraries\Modulelang;

class CompaniesCurrency extends Model
{
    use HasFactory;
    protected $table = "companies_currency";
    protected $fillable = ["name","flag","currency_name","currency_sign","status","created_at"];

    public function getAllList()
    {
        $db_connect = DB::connection('mysql');
        $companies_industry_array = [];
        $companies_timezone_array = [];
        // $companies_currency = $db_connect->table('companies_currency')->where('status','=', 2)->get();
        $companies_industry = $db_connect->table('companies_industry')->get()->toArray();
        $companies_timezone = $db_connect->table('companies_timezone')->get()->toArray();
        if ( is_array($companies_industry) && count($companies_industry) > 0 ) {
            foreach( $companies_industry as $q ) {
                $companies_industry_array[] = [
                                                'id' => $q->id,
                                                'name' => Modulelang::industry($q->id),
                                                'created_at' => $q->created_at,
                                            ];
            }
        }
        if ( is_array($companies_timezone) && count($companies_timezone) > 0 ) {
            foreach( $companies_timezone as $tq ) {
                $companies_timezone_array[] = [
                                                'id' => $tq->id,
                                                'name' => Modulelang::timezone($tq->id),
                                                'zone_value' => $tq->zone_value,
                                                'created_at' => $tq->created_at,
                                            ];
            }
        }
        return [ 
                    // 'companies_currency' => $companies_currency,
                    'companies_industry' => $companies_industry_array,
                    'companies_timezone' => $companies_timezone_array
                ];
    }
    
}
