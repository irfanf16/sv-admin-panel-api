<?php

namespace Modules\Plans\Http\Requests;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rule;
use Modules\Plans\Rules\TiersHasInf;
class SubscriptionAddRequest extends AbstractFormRequest
{
    public function rules()
    {
        $validations = [
            'stripe_customer_id' => 'required',
            'company_id' => 'required',
            'user_id' => 'required',
            'price_ids' => 'required|array'
        ];
        return $validations;
    }
}
