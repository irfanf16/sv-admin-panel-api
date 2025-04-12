<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Articles;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Models\ModuleFeatureList;
use App\Traits\Historable;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\ArticlesPresenter;

class ArticlesPages extends Model
{
    use SoftDeletes;
    use Historable;
    use PresentableTrait;

    protected $table = 'article_pages';
    protected $primaryKey = "id";
    protected $fillable = ["title","body","article_id","status","is_published",'deleted_at','created_at','updated_at'];
    protected $presenter = ArticlesPresenter::class;
   
    public function featuresList()
    {
        return $this->belongsTo(Articles::class,'id');
    }
}
