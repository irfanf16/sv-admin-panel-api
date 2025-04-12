<?php
namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'company_title' => 'required|min:1',
            'owner_email' => 'required',
//            'owner_name' => 'required',
            "plan_id" => 'required',
        ];
    }
}
