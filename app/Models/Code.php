<?php

namespace App\Models;
use Carbon\Carbon;

class Code extends Base
{
    protected $table = 'portal_codes';
    public function scopeValidateCode($query, $code, $fields = [])
    {
        $allFields = [
            "portal_codes.id",
            "code",
            "expiry_at",
            "portal_codes.user_ip_address",
            "user_mac_address",
            "users.first_name",
            "users.last_name",
            "users.email",
            "companies.title",
            "portal_codes.user_id",
            "portal_codes.company_id",
        ];

        if(!empty($fields)) {
            $allFields = array_unique(array_merge($allFields, $fields));
        }
        
        $query = $query->select($allFields);
        $query = $query->leftJoin("users", "users.id", "=", "portal_codes.user_id");
        $query = $query->leftJoin("companies", "companies.id", "=", "portal_codes.company_id");
        $query = $query->where('code', $code)->where('expiry_at', '>=', Carbon::now());
        $query->where(function($query) {
            $query = $query->where('agent_id', 0);
            $query = $query->orwhere('agent_id', null);
        });
        

        return $query;
    }
}
