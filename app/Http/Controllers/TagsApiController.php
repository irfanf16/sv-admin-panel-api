<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FilterOr;
use App\Models\Tag;

class TagsApiController extends BaseApiController
{
    public function index(Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(Tag::class)
            ->selectFields($request->input('fields.tags'))
            ->allowedSorts(['tag', 'uses'])
            ->allowedFilters([
                AllowedFilter::custom('tag', new FilterOr()),
            ])
            ->addSelect([
                'uses' => DB::table('taggables')->selectRaw('COUNT(*)')->whereColumn('tags.id', 'taggables.tag_id'),
            ])
            ->paginate($request->input('per_page'));

        return $data;
    }

    public function tagsList(Request $request)
    {
        $models = QueryBuilder::for(Tag::class)
            ->get();

        return $models;
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
    }
}
