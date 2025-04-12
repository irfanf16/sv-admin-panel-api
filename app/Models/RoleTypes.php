<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleTypes extends Model
{
    use HasFactory;

    protected $fillable = ['id','role_id','role_type'];
    protected $table = "role_types";
}
