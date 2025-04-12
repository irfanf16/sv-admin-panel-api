<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Historable;

class UserTimeLog extends Base
{
    use Historable;

    protected $table = 'user_time_log';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'time_logs' => 'array',
    ];

    public function scopeTimelogsDetails($query, $company_id = 0, $search = '', $fields = [])
    {
        if(empty($fields)) {
            $fields = [
                "user_time_log.id",
                "user_time_log.company_id",
                "companies.title",
                "user_id",
                "email",
                "first_name",
                "last_name",
                "user_time_log.created_at",
                "user_time_log.updated_at",
                "exception",
                "time_logs"
            ];
        }
        $query->select($fields)
            ->leftJoin('users', 'users.id', '=', 'user_time_log.user_id')
            ->leftJoin('companies', 'companies.id', '=', 'user_time_log.company_id');
        if($company_id > 0) {
            $query = $query->where('company_id', $company_id);
        }
        if(!empty($search)) {
            $query->where(function($query) use ($search) {
                $query = $query->orwhere('time_logs', 'LIKE', '%'.$search.'%');
                $query = $query->orwhere('email', 'LIKE', '%'.$search.'%');
                $query = $query->orwhere('companies.title', 'LIKE', '%'.$search.'%');
                $query = $query->orwhere('exception', 'LIKE', '%'.$search.'%');
            });
        }
        return $query->orderBy('user_time_log.id', 'desc');
    }
}
