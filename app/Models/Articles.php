<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ArticlesPages;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Models\ModuleFeatureList;
use App\Traits\Historable;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\ArticlesPresenter;

class Articles extends Model
{
    use SoftDeletes;
    use Historable;
    use PresentableTrait;

    protected $table = 'articles';
    protected $primaryKey = "id";
    protected $fillable = ["title","description","status","created_by","is_published",'deleted_at','created_at','updated_at'];
    protected $presenter = ArticlesPresenter::class;
   
    public function pages()
    {
        return $this->hasMany(ArticlesPages::class,'article_id');
    }
    public function scopeArticlesWithPages($query, $article_id = 0, $fields=[], $search = '', $status = '')
    {
        
        $fields = [
            "articles.id as article_id",
            "articles.title as article_title",
            "description",
            "created_by",
            "articles.status as article_status",
            "is_published",
            "article_pages.id as page_id",
            "body",
            "article_pages.status as page_status",
        ];
        $query = $query->select($fields)->join('article_pages', 'article_pages.article_id', '=', 'articles.id');
        if($article_id) {
            $query = $query->where('article_id', $article_id);
        }
        
        if(!empty($search)) {
            $query->where(function($query) use ($search) {
                $query = $query->where('articles.title', 'LIKE', '%'.$search.'%');
            });
        }
        if(!empty($status)) {
            $query = $query->where('articles.status', $status);
        }
        // $query = $query->orderBy('first_name', 'asc')->orderBy('last_name', 'asc');
        return $query;
    }
}
