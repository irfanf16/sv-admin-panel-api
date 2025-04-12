<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FilterOr;
use App\Models\Taxonomy;
use App\Models\Term;

class TermsApiController extends BaseApiController
{
    public function index(Taxonomy $taxonomy, Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(Term::class)
            ->selectFields($request->input('fields.terms'))
            ->where('taxonomy_id', $taxonomy->id)
            ->allowedSorts(['title_translated', 'position'])
            ->allowedFilters([
                AllowedFilter::custom('title', new FilterOr()),
            ])
            ->paginate($request->input('per_page'));

        return $data;
    }

    protected function updatePartial(Taxonomy $taxonomy, Term $term, Request $request)
    {
        foreach ($request->only('position') as $key => $content) {
            if ($term->isTranslatableAttribute($key)) {
                foreach ($content as $lang => $value) {
                    $term->setTranslation($key, $lang, $value);
                }
            } else {
                $term->{$key} = $content;
            }
        }

        $term->save();
    }

    public function destroy(Taxonomy $taxonomy, Term $term)
    {
        $term->delete();
    }
}
