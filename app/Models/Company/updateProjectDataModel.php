<?php

namespace App\Models\Company;

use App\Group;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class updateProjectDataModel extends Model
{
    use HasFactory;
    protected $table = "projects";
    protected $fillable = ["uuid","title", "description", "active", "billable", "type", "budget", "sync_frequency", "company_id", "is_deleted", "created_by", "updated_by", "color"];

    public function projectUuidUpdate(){

        $uuidProjects = Project::where('uuid', 0)->get();
        if ($uuidProjects) {
            foreach ($uuidProjects as $pro) {
                $pro->update(['uuid' => (string)Str::uuid()]);
                if ($pro){
                    dump('Project Id: '.$pro->id. ' , Title: '. $pro->title.', uuid: '. $pro->uuid.' = updated.');
                }

            }
        }
    }

}
