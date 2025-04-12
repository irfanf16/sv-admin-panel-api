<?php

namespace App\Http\Requests;

class TranslationFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'key' => 'required|max:255|unique:portal_translations,key,'.$this->id,
            'translation.*' => 'nullable',
        ];
    }
}
