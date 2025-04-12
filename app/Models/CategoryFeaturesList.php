<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Presenters\CategoryFeaturesListPresenter;
use Laracasts\Presenter\PresentableTrait;
use App\Traits\Historable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CategoryFeatures;
class CategoryFeaturesList extends Model
{
    use SoftDeletes, PresentableTrait, Historable;
    protected $presenter = CategoryFeaturesListPresenter::class;
    protected $primaryKey = "id";
    protected $table = 'category_features_list';
    protected $fillable = ["category_id","feature_title","plan", "plan_value","deleted_at","created_at","updated_at"];
    protected $casts = [
        'plan' => 'array',
        'plan_value' => 'array'
    ];
   /**
     * Get the features that owns the feature list.
     */
    public function features(): BelongsTo
    {
        return $this->belongsTo(CategoryFeatures::class, 'category_feature_id');
    }
}
