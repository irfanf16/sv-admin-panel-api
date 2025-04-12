<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\FileFormRequest;
use App\Models\File;

class FilesAdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('files::admin.index');
    }

    public function edit(File $file): View
    {
        return view('files::admin.edit')->with(['model' => $file]);
    }

    public function update(File $file, FileFormRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $file->update($data);

        return $this->redirect($request, $file);
    }
}
