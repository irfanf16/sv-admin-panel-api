<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Presenters\CategoryFeaturesPresenter;
use Laracasts\Presenter\PresentableTrait;
use App\Traits\Historable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CategoryFeaturesList;
class CategoryFeatures extends Model
{
    use SoftDeletes, PresentableTrait, Historable;
    protected $presenter = CategoryFeaturesPresenter::class;
    protected $primaryKey = "id";
    protected $table = 'category_features';
    protected $fillable = ["category_id","title","deleted_at","created_at","updated_at"];

    /**
     * Get the comments for the blog post.
     */
    public function featureList(): HasMany
    {
        return $this->hasMany(CategoryFeaturesList::class, "category_feature_id");
    }
}
