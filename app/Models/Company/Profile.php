<?php

namespace App\Models\Company;

use App\Libraries\Generic;
use Database\Seeders\ProfilesTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Profile extends Model
{
    protected $table = "profiles";
    protected $fillable = ["title", "description", "allow_tracking", "profile_type","created_by"];
    public $timestamps = true;
    public function modules()
    {
        return $this->belongsToMany('App\Models\Company\Module', 'profile_modules');
    }

    public function modules_permisions()
    {
        return $this->hasOne('App\Models\Company\ProfileModules', 'profile_id','id');
    }
    public function modules_permisionss()
    {
        return $this->hasManyThrough('App\Models\Company\Module', 'App\Models\Permission',
            'module_id','permission_id','id');
    }

    public function profile_in_company_users($fromDeletion = false){
        $query = $this->hasMany('App\Models\UserCompanies', 'profile_id','id')->where('is_deleted' , '=',0);

        if($fromDeletion)
        {
            $query->where('status' ,'!=','deactive');
        }
        return $query;
    }

    public function permissions(){
        return $this->belongsToMany('App\Models\Company\Permission', 'profile_modules')->withPivot('permission_id', 'profile_id','module_id  ');
    }
    public function projects(){
        return $this->belongsToMany('App\Models\Company\Project', 'user_projects','profile_id');
    }

    public function profileType(){
        return $this->belongsTo(ProfileTypes::class,'profile_type');
    }
}
