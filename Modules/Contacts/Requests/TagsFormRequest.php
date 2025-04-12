<?php

namespace App\Http\Requests;

class TagsFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'tag' => 'required|max:255',
            'slug' => 'required|max:255|alpha_dash',
        ];
    }
}
