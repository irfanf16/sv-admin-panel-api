<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Presenters\CategoryPresenter;
use Laracasts\Presenter\PresentableTrait;
use App\Traits\Historable;

class SystemFeature extends Model
{
    use SoftDeletes, PresentableTrait, Historable;
    protected $presenter = CategoryPresenter::class;
    protected $primaryKey = "id";
    protected $table = 'categories';
    protected $fillable = ["title","description", "proration", "price_type", "discount_type", "frequency","deleted_at","created_at","updated_at"];
}
