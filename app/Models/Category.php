<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Presenters\CategoryPresenter;
use Laracasts\Presenter\PresentableTrait;
use App\Traits\Historable;

class Category extends Model
{
    use SoftDeletes, PresentableTrait, Historable;
    protected $presenter = CategoryPresenter::class;
    protected $primaryKey = "id";
    protected $table = 'categories';
    protected $fillable = ["title","description", "proration", "price_type", "discount_type", "frequency","published","deleted_at","created_at","updated_at"];

    protected $casts = [
        'frequency' => 'array'
    ];
    public function scopeGetCategories($query, $params) {
        if(isset($params['search']) && !empty($params['search'])) {
            $search = $params['search'];
            $query->where('title', 'LIKE', "%{$search}%");
            $query->orwhere('description', 'LIKE', "%{$search}%");
        }

        if(isset($params['published']) && !empty($params['published'])) {
            $query->where('published',$params['published']);
        }

        return $query;
    }
}
