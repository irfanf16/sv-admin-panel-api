<?php

namespace App\Models\Company;

use App\Libraries\Generic;
use Illuminate\Database\Eloquent\Model;

class ProfileTypes extends Model
{
    protected $table = "profile_types";
    protected $fillable = ["title", "description"];
}
