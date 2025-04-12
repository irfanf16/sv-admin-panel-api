<?php

namespace App\Models;

use App\Libraries\Generic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instance extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "instances";
    protected $host;
    protected $fillable = ["instance","db_host","db_port","db_username","db_password","db_driver","deleted_at","created_at","updated_at"];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $connection = 'mysql';


    public function instanceCompanies(){
        return $this->hasMany('App\Models\Company', 'instance_id');
    }

    public function setDbHostAttribute($value)
    {
        $this->attributes['db_host'] = \DB::raw("AES_ENCRYPT('$value', '".env("ENC_KEY")."')");
    }
    public function setDbPortAttribute($value)
    {
        $this->attributes['db_port'] = \DB::raw("AES_ENCRYPT('$value', '".env("ENC_KEY")."')");
    }

    public function setDbDriverAttribute($value)
    {
        $this->attributes['db_driver'] = \DB::raw("AES_ENCRYPT('$value', '".env("ENC_KEY")."')");
    }
    public function setDbUsernameAttribute($value)
    {
        $this->attributes['db_username'] = \DB::raw("AES_ENCRYPT('$value', '".env("ENC_KEY")."')");
    }
    public function setDbPasswordAttribute($value)
    {
        $this->attributes['db_password'] = \DB::raw("AES_ENCRYPT('$value', '".env("ENC_KEY")."')");
    }

//    public function getDbHostAttribute($value)
//    {
//       return \DB::raw("AES_DECRYPT('$value', 'fd434ed844ecb38101904d427fdb0aa0')");
//
//        //dd($this->attributes['db_host']);
//    }

    public function scopeDecrypt($query)
    {
        $query->select(\DB::raw('AES_DECRYPT(db_host,"'.env('ENC_KEY').'") as db_host,
        id,created_at,updated_at,
            AES_DECRYPT(db_port,"'.env('ENC_KEY').'") as db_port,AES_DECRYPT(db_driver,"'.env('ENC_KEY').'") as db_driver,
            AES_DECRYPT(db_username,"'.env('ENC_KEY').'") as db_username,AES_DECRYPT(db_password,"'.env('ENC_KEY').'") as db_password,
            AES_DECRYPT(db_username,"'.env('ENC_KEY').'") as db_username, instance
            ')
        );
    }
}
