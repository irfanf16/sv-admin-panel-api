<?php

namespace Modules\Plans\Http\Requests;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rule;
use Modules\Plans\Rules\TiersHasInf;
class ProductsAddRequest extends AbstractFormRequest
{
    public function rules()
    {
        $validations = [
            'name' => 'required',
            "active" => 'boolean',

            // metadata validation
            'metadata' => 'required|array',
            'metadata.type' => ['required',Rule::in(["addons", "plans"])],
            'metadata.public' => ['required',Rule::in(["0", "1"])],
            'metadata.base_price' => 'exclude_unless:metadata.type,"plans"|required|numeric|gte:0',
            // Trials Validation
            'metadata.trial_period_days' => 'exclude_unless:metadata.type,"plans"|nullable|numeric|gte:0',

            // Following metadata is not for the admin.
            'metadata.modules' => 'prohibited',
            'metadata.module_features_list' => 'prohibited',
            'metadata.addons' => 'prohibited',

            // Addons Validation
            'addons' => 'exclude_unless:metadata.type,"addons"|nullable|array',

            // Prices validation
            'prices' => 'nullable|array',
            "prices.*.billing_scheme" => ['required',Rule::in(["per_unit", "tiered"])],
            'prices.*.recurring' => ['nullable',Rule::in(["day", "month", "week", "year", "bi-annually", "quarterly"])],
            'prices.*.unit_amount' => 'exclude_unless:prices.*.billing_scheme,"per_unit"|required|numeric|gte:0',

            // tiers validation
            'prices.*.tiers' => 'exclude_unless:prices.*.billing_scheme,"tiered"|required|array',
            'prices.*.tiers.*.flat_amount' => 'exclude_unless:prices.*.billing_scheme,"tiered"|required|numeric|gte:0',
            'prices.*.tiers.*.up_to' => ['exclude_unless:prices.*.billing_scheme,"tiered"', 'required', new TiersHasInf],
        ];
        if(isset($this->metadata['type']) && $this->metadata['type'] == 'plans') {
            $validations['metadata.minimum_users'] = 'nullable';
            $validations['metadata.maximum_users'] = 'nullable';
        }

        return $validations;
    }
}
