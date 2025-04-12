<?php

namespace Modules\Plans\Http\Requests;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rule;

class CouponsAddRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'amount_off' => 'required',
            'percent_off' => ['required',Rule::in(["true", "false"])],
            'duration' => ['required',Rule::in(["forever", "once", "repeating"])],
            'duration_in_months' => $this->duration == 'repeating' ? 'required|numeric|gt:0' : '',
            "max_redemptions" => 'nullable|numeric|gt:0',
            "redeem_by" => 'nullable|date',
            'metadata' => 'nullable|array',
            'metadata.*.key' => 'nullable',
            'metadata.*.value' => 'nullable',
        ];
    }
}
