<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscriptionDetail extends Model
{
    use HasFactory;
    protected $table = "user_subscription_details";

    protected $fillable = [
        'user_id',
        'ip_address',
        'os_info',
        'location',
        'terms_acceptance_time',
        'terms_details',
    ];
}
