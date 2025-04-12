<?php

namespace App\Http\Requests;

class AffiliateRegistrationFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,dns|max:255',
            'name' => 'required|max:255',
            'password' => 'required',
            'publicId.value' => 'required',
        ];
    }
}
