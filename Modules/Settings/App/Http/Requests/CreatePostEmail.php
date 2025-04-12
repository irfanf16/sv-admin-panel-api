<?php

namespace Modules\Settings\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePostEmail extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'subject' => 'required',
            'service' => ['required',Rule::in(['trial','active_plan','card', 'cleanup','addons','bucket','subscription','invite_user', 'forgot_password'])],
            'trial' => ['nullable',Rule::in(['start','complete_plan_active','in_progress','trial_expire','cancel','payment_declined','payment_post_grace_declined',
                'trial_plan_successful_payment', 'payment_post_grace_end_declined','compnay_setup'])],
            'active_plan' => ['nullable',Rule::in(['payment_declined','payment_successful','last_day_of_post_grace_period','cancel_subscription',
                'payment_successfully_closure_plan','payment_post_grace_declined','compnay_setup'])],
            'card' => ['nullable',Rule::in(['close_to_expiry','expired','card_updation'])],
            'cleanup' => ['nullable',Rule::in(['instance_cleanup','closure_plan'])],
            'addons' => ['nullable',Rule::in(['purchase_add_ons','expire_add_ons'])],
            'bucket' => ['nullable',Rule::in(['buy'])],
            'occurrence' => ['required',Rule::in(['1', '2'])],
            'days' => 'integer|gte:0',
            'status' => ['required',Rule::in(['1', '0'])],
            'email_body' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
