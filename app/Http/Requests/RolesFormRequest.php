<?php

namespace App\Http\Requests;

class RolesFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|min:4|max:255|unique:portal_roles,name,'.$this->id,
        ];
    }
}
