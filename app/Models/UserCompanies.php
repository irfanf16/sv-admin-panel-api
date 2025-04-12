<?php

namespace App\Models;
use App\Traits\Historable;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\CompanyUserPresenter;

class UserCompanies extends Model
{
    use Historable;
    use PresentableTrait;
   
    protected $presenter = CompanyUserPresenter::class;
    protected $table = "companies_users";
    protected $fillable = [
                            "user_id", 
                            "company_id", 
                            "profile_id",
                            "tiny_url",
                            "url_expiry", 
                            "profile_name", 
                            "profile_type", 
                            "status", 
                            "is_deleted", 
                            "allow_tracking", 
                            "is_terminated", 
                            "status_comments", 
                            "is_employee", 
                            "capture_screen", 
                            "web_tracking", 
                            "idle_time_tracking", 
                            "default_approval", 
                            "blocked_by_super_admin"
                        ];

    public function company()
    {
        $this->hasOne('App\Model\Company', 'company_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function update_setting($user_id, $field)
    {
        $this->where('user_id', $user_id)->update($field);
        return true;
    }

    public function update_users_terminate($company_id,$user_id, $field=[])
    {
        $this->where('company_id', $company_id)->where('user_id', $user_id)->update($field);
        return true;
    }

}
