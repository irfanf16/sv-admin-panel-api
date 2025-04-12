<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FilterOr;
use App\Models\Menu;

class MenusApiController extends BaseApiController
{
    public function index(Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(Menu::class)
            ->selectFields($request->input('fields.menus'))
            ->allowedSorts(['status_translated', 'name'])
            ->allowedFilters([
                AllowedFilter::custom('name', new FilterOr()),
            ])
            ->allowedIncludes(['image'])
            ->paginate($request->input('per_page'));

        return $data;
    }

    protected function updatePartial(Menu $menu, Request $request)
    {
        foreach ($request->only('status') as $key => $content) {
            if ($menu->isTranslatableAttribute($key)) {
                foreach ($content as $lang => $value) {
                    $menu->setTranslation($key, $lang, $value);
                }
            } else {
                $menu->{$key} = $content;
            }
        }

        $menu->save();
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
    }
}
