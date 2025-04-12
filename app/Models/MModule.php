<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company\Module as  modules;
use App\Models\ModuleFeature;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use App\Models\ModuleFeatureList;

class MModule extends Model
{
    use SoftDeletes;
    

    protected $primaryKey = "id";
    protected $fillable = ["title", "description", "url", "icon", "parent_module_id",'deleted_at','created_at','updated_at','module_order','module_type'];


    // public function subModules()
    // {
    //     return $this->hasMany(modules::class,'parent_module_id');
    // }

    // public function parentModulefeatures()
    // {
    //     return $this->hasMany(ModuleFeature::class,'parent_module_id');
    // }

    // public function subModuleFeatures()
    // {
    //     return $this->hasMany(ModuleFeature::class,'sub_module_id');
    // }
    // public function featuresList(): HasOneThrough
    // {
    //     return $this->hasOneThrough(ModuleFeatureList::class,ModuleFeature::class,'sub_module_id','module_features_id');
    // }
}
