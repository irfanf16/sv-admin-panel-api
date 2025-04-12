<?php

namespace Modules\Plans\Http\Requests;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rule;

class FeatureAddRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'parent_module_id' => 'required',
            'sub_module_id' => 'required',
            'status' => 'required',
            'module_features_list' => 'required|array',
            'module_features_list.*.type' => ['nullable',Rule::in(['1', '2'])],
            'module_features_list.*.feature_key' => 'string',
            'module_features_list.*.feature_label' => 'nullable',
            'module_features_list.*.status' => ['nullable',Rule::in(['1', '0'])]
        ];
    }
}
