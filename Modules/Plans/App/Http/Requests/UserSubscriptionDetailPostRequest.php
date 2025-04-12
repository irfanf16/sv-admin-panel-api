<?php

namespace Modules\Plans\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSubscriptionDetailPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
                'user_id' => 'required',
                'ip_address' => 'required',
                'os_info' => 'nullable',
                'location' => 'nullable',
                'terms_acceptance_time' => 'required',
                'terms_details' => 'string',
            ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
