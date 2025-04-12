<?php

namespace App\Http\Requests;

class MasterUsersAddFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|unique:users,email',
        ];
    }
}
