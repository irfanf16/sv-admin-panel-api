<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ModuleFeature;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFiles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\File;

class ModuleFeatureList extends Model
{
    use HasFiles;
    use SoftDeletes;

    protected $table = "module_features_list";
    protected $primaryKey = "id";
    protected $fillable = ["module_features_id", "type", "feature_key","rule", "feature_value",'feature_label','status','content','image','deleted_at','created_at','updated_at','sort_by'];

    public function getImageAttribute($value)
    {
        if(!empty($value)) {
            return asset($value);
        }
        return $value;
    }
    // public function setImageAttribute($value)
    // {
    //     return $value;
    // }
    public function modulefeatures()
    {
        return $this->belongsTo(ModuleFeature::class,'id');
    }
    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image');
    }
    public function scopeGetFeaturesWithModuleIds($query, $feature_list_ids =[]) {
        $query = $query->join('module_features', 'module_features.id' , '=', 'module_features_list.module_features_id');
        if(!empty($feature_list_ids)) {
            $query = $query->wherein('module_features_list.id', $feature_list_ids);
        }
        return $query;
    }
    public function scopeGetType($query, $type = 2) {
        return $query->where('type', $type);
    }
    public function scopeGetFeaturesList($query, $module_features_list=[]) {
        $query->select([
            "module_features_list.id",
            "module_features_list.module_features_id",
            "module_features_list.type",
            "module_features_list.rule",
            "module_features_list.feature_key",
            "module_features_list.feature_value",
            "module_features_list.feature_label",
            "module_features_list.status",
            "module_features_list.content",
            "module_features_list.image",
            "module_features.parent_module_id",
            "module_features.sub_module_id",
            "module_features_list.created_at",
            "module_features_list.updated_at",
            "module_features_list.sort_by",
        ])->getFeaturesWithModuleIds($module_features_list);
        return $query;
    }
}
