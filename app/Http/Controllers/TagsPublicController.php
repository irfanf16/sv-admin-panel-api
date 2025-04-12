<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Tag;

class TagsPublicController extends BasePublicController
{
    public function index(): View
    {
        $perPage = config('typicms.modules.tags.per_page');
        $models = Tag::paginate($perPage);

        return view('tags::public.index')
            ->with(compact('models'));
    }

    public function show($slug): View
    {
        $model = Tag::where('slug', $slug)->firstOrFail();

        return view('tags::public.show')
            ->with(compact('model'));
    }
}
