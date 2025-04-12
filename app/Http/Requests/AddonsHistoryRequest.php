<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class AddonsHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'addons_history.*.company_id' => 'required', 
            'addons_history.*.subscription_id' => 'required',
            'addons_history.*.stripe_price_id' => 'required',
        ];
    }
}
