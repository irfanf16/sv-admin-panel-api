<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemFeature extends Model
{
    use SoftDeletes;
    protected $primaryKey = "pf_id";
    protected $table = 'system_features';
    protected $fillable = ["package_id","parent_module_id", "sub_module_id", "feature_key", "feature_value", "status","deleted_at","created_at","updated_at"];
}
