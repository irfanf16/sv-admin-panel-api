<?php

namespace Modules\Plans\Http\Requests;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rule;

class SubscriptionUpdateRequest extends AbstractFormRequest
{
    public function rules()
    {
        $validations = [
            // 'stripe_id' =>  'required',
            // "active" => 'boolean',
            // "billing_scheme" => ['nullable',Rule::in(["per_unit", "tiers"])],
            // 'nickname' => 'nullable|string',
            // 'metadata' => 'required|array',
            // 'metadata.type' => ['required',Rule::in(["addons", "plans"])],
            // 'metadata.public' => ['required',Rule::in(["0", "1"])],
            // 'features' => 'nullable|array',
            // 'prices' => 'nullable|array',
            // 'prices.*.unit_amount' => 'nullable|numeric|gt:0',
            // 'prices.*.recurring' => ['nullable',Rule::in(["day", "month", "week", "year"])],
        ];
        if(isset($this->metadata['type']) && $this->metadata['type'] == 'plans') {
            // $validations['metadata.minimum_users'] = 'required|numeric|gt:0';
            // $validations['metadata.maximum_users'] = 'required|numeric|gt:0';
        }
        return $validations;
    }
}
