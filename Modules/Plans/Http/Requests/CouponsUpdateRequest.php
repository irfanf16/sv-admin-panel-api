<?php

namespace Modules\Plans\Http\Requests;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rule;

class CouponsUpdateRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'metadata' => 'nullable|array',
            'metadata.*.key' => 'nullable',
            'metadata.*.value' => 'nullable',
        ];
    }
}
