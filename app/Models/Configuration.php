<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $table = 'configurations';
    protected $fillable = ["trial_plan_grace_period", "active_plan_grace_period", "placeholder"];

}
