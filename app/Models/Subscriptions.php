<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $table = 'subscriptions';
    protected $fillable = ["company_id", "super_admin_id", "subscription_id", "price_id", "plan_id","plan_staus", "plan_expiry", "grace_period_start"];

}
