<?php

namespace Modules\Files\Http\Controllers;

use Modules\Files\Http\Requests\FileFormRequest;
use Modules\Files\Models\File;
use App\Http\Controllers\BaseAdminController;

class FilesAdminController extends BaseAdminController
{
    public function index()
    {
        // return view('files::admin.index');
    }

    public function edit(File $file)
    {   
        return ['model' => $file];
    }

    public function update(File $file, FileFormRequest $request)
    {
        $data = $request->validated();
        $file->update($data);
        return $file;
        // return $this->redirect($request, $file);
    }
}
