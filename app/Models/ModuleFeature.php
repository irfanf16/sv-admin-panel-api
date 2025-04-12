<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company\Module as  modules;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ModuleFeatureList;
use App\Traits\Historable;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\ModuleFeaturePresenter;


class ModuleFeature extends Model
{
 
    use SoftDeletes;
    use Historable;
    use PresentableTrait;

    protected $primaryKey = "id";
    protected $fillable = ["parent_module_id", "sub_module_id", "status",'deleted_at','created_at','updated_at'];
    protected $presenter = ModuleFeaturePresenter::class;
    public function modules()
    {
        return $this->belongsTo(modules::class,'id');
    }

    public function featuresList()
    {
        return $this->hasMany(ModuleFeatureList::class,'module_features_id');
    }

    
}
