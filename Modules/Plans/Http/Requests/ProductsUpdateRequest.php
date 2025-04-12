<?php

namespace Modules\Plans\Http\Requests;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rule;

class ProductsUpdateRequest extends AbstractFormRequest
{
    public function rules()
    {
        $validations = [
            'stripe_id' =>  'required',
            "active" => 'boolean',
            "billing_scheme" => ['nullable',Rule::in(["per_unit", "tiers"])],
            'nickname' => 'nullable|string',
            'metadata' => 'required|array',
            'metadata.type' => ['required',Rule::in(["addons", "plans"])],
            'metadata.public' => ['required',Rule::in(["0", "1"])],
            // Trials Validation
            'metadata.trial_period_days' => 'exclude_unless:metadata.type,"plans"|nullable|numeric|gte:0',
            'metadata.base_price' => 'exclude_unless:metadata.type,"plans"|required|numeric|gte:0',

            // Following metadata is not for the admin.
            'metadata.modules' => 'prohibited',
            'metadata.module_features_list' => 'prohibited',
            'metadata.addons' => 'prohibited',

            // Addons Validation
            'addons' => 'exclude_unless:metadata.type,"addons"|nullable|array',

            'features' => 'nullable|array',
            'prices' => 'nullable|array',
            'prices.*.unit_amount' => 'nullable|numeric|gte:0',
            'prices.*.recurring' => ['nullable',Rule::in(["day", "month", "week", "year", "bi-annually", "quarterly"])],
        ];
        if(isset($this->metadata['type']) && $this->metadata['type'] == 'plans') {
            $validations['metadata.minimum_users'] = 'nullable';
            $validations['metadata.maximum_users'] = 'nullable';
        }
        return $validations;
    }
}
