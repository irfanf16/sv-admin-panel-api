<?php

namespace Modules\Plans\Http\Requests;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rule;
use Modules\Plans\Rules\TiersHasInf;
class SubscriptionItemsRequest extends AbstractFormRequest
{
    public function rules()
    {
        $validations = [
            'user_id' => 'required',
            'price_ids' => 'required|array'
        ];
        return $validations;
    }
}
