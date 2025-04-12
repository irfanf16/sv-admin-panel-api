<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\PageFormRequest;
use App\Models\Page;

class PagesAdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('pages::admin.index');
    }

    public function create(): View
    {
        $model = new Page();

        return view('pages::admin.create')
            ->with(compact('model'));
    }

    public function edit(Page $page)
    {
        return $page;
        // return view('pages::admin.edit')
        //     ->with(['model' => $page]);
    }

    public function store(PageFormRequest $request)
    {
        $page = Page::create($request->validated());
        return $page;
        // return $this->redirect($request, $page)
        //     ->withMessage(__('Item successfully created.'));
    }

    public function update(Page $page, PageFormRequest $request)
    {
        $page->update($request->validated());
        return $page;
        // return $this->redirect($request, $page)
        //     ->withMessage(__('Item successfully updated.'));
    }

    public function notFound()
    {
        abort(404);
    }
}
