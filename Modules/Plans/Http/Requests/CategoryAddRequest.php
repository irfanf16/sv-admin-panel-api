<?php

namespace Modules\Plans\Http\Requests;
use Illuminate\Validation\Rule;
use App\Http\Requests\AbstractFormRequest;
class CategoryAddRequest extends AbstractFormRequest
{
    public function rules()
    {
        $validations = [
            'title' => 'required|max:255|min:3',
            "proration" => 'required|boolean',
            "price_type" => ['required',Rule::in(['per_unit', 'one_time', 'tiers','one_time_all_users'])],
            "discount_type" => ['required',Rule::in(['percentage', 'fixed'])],
            "frequency" => 'nullable|array'
        ];
        return $validations;
    }
}
