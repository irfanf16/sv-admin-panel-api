<?php

namespace App\Models;


use App\Models\UserCompanies;

use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Support\Facades\DB;
use App\Traits\Historable;
use App\Presenters\MasterUsersPresenter;

class UserTableMaster extends Authenticatable
{
    use Historable, PresentableTrait,Notifiable;

    protected $presenter = MasterUsersPresenter::class;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'activated',
        'password',
        'superuser',
        'allow_tracking',
        'privacy_policy_accepted',
        'rate',
        'bill',
        'image',
        'macAddress',
        'weekly_limit',
        'remember_token',
        'theme_color',
        'current_status',
        'current_task_id',
        'last_updated',
        'client_app_version',
        'timezone',
        'app_password',
        'locale',
        'street',
        'number',
        'box',
        'postal_code',
        'city',
        'country',
        'preferences',
        'api_token',
        'email_verified_at',
        'remember_token',
        'created_at',
        'updated_at',
        'stripe_customer_id',
    ];
    protected $table = "users";
    // protected $hidden = array('password', 'app_password', 'remember_token');
    public function usercompanies()
    {
        return $this->belongsToMany('App\Models\Company', 'companies_users');//->withPivot('status', 'profile_id', 'profile_name', 'profile_type');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getCompanyUsers()
    {
        $users_list = $this->join('companies_users', 'companies_users.user_id', '=', 'users.id')
            ->select(DB::raw('users.id,users.first_name, users.last_name, users.email'))
            ->where(['is_deleted' => 0, 'status' => 'active', 'is_terminated' => 0, 'company_id' => session('company_id')])->get();
        return $users_list;
    }

    public function companiesusers()
    {
        return $this->hasMany(UserCompanies::class);
    }

    public function getMasterUserEmail($user_id)
    {
        $sql = $this->find($user_id);
        if (!empty($sql)) {
            return $sql->email;
        }
        return NULL;
    }

}
