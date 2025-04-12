<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FilterOr;
use App\Models\Page;
use App\Models\PageSection;

class PageSectionsApiController extends BaseApiController
{
    public function index(Page $page, Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(PageSection::class)
            ->selectFields($request->input('fields.page_sections'))
            ->allowedSorts(['status_translated', 'position', 'title_translated'])
            ->allowedFilters([
                AllowedFilter::custom('title', new FilterOr()),
            ])
            ->allowedIncludes(['image'])
            ->where('page_id', $page->id)
            ->paginate($request->input('per_page'));

        return $data;
    }

    protected function updatePartial(Page $page, PageSection $section, Request $request)
    {
        foreach ($request->only('status', 'position') as $key => $content) {
            if ($section->isTranslatableAttribute($key)) {
                foreach ($content as $lang => $value) {
                    $section->setTranslation($key, $lang, $value);
                }
            } else {
                $section->{$key} = $content;
            }
        }

        $section->save();
    }

    public function destroy(Page $page, PageSection $section)
    {
        $section->delete();
    }
}
