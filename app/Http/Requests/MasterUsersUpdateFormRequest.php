<?php

namespace App\Http\Requests;

class MasterUsersUpdateFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        // dd($this->id);
        return [
            // 'id' => 'exists:users,id', //.$this->id,
        ];
    }
}
