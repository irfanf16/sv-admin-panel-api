<?php

namespace App\Http\Requests;

class AddRememberTokenRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,dns|exists:users,email',
        ];
    }
}
