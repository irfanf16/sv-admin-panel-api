<?php

namespace Modules\Plans\Http\Requests;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rule;
class CustomerAddRequest extends AbstractFormRequest
{
    public function rules()
    {
       
        $validations = [
            "email" => 'required|email',
        ];
      
        return $validations;
    }
}
