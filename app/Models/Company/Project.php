<?php

namespace App\Models\Company;

use App\Group;
use App\User;
use App\Libraries\Generic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PharIo\Version\Exception;
use DateTime;
// use App\Events\NewProject;
// use App\Events\UpdateProject;
class Project extends Model
{

    protected $table = "projects";
    protected $fillable = ["uuid", "title", "description", "active", "billable", "type", "budget", "sync_frequency", "company_id", "is_deleted", "created_by", "updated_by", "color"];
    protected $appends = ['cost'];
    protected $sum;
    protected $rate;
    protected $primaryKey = "id";
    protected $user_activites = [];
    protected $time_zone_able = ['created_at', 'updated_at'];

    

}
