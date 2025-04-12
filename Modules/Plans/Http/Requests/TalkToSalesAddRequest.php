<?php

namespace Modules\Plans\Http\Requests;

use App\Http\Requests\AbstractFormRequest;
use Illuminate\Validation\Rule;
class TalkToSalesAddRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'first_name'    => 'required|max:255',
            'last_name'     => 'required|max:255',
            'company'       => 'required|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|max:255',
            'reason'        => 'required|max:255',
            'message'       => 'required'
        ];
    }
}
