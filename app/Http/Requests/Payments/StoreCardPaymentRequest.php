<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCardPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cc_name' => ['required', 'string', 'max:120'],
            'cc_number' => ['required', 'string', 'regex:/^[0-9\\s-]{13,23}$/'],
            'cc_exp_month' => ['required', 'regex:/^(0[1-9]|1[0-2])$/'],
            'cc_exp_year' => ['required', 'digits:4', 'integer', 'min:' . now()->year, 'max:' . now()->addYears(20)->year],
            'cc_cvv2' => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
            'cc_type' => ['required', Rule::in(['visa', 'mastercard'])],
            'billing_address' => ['required', 'string', 'max:191'],
            'billing_city' => ['required', 'string', 'max:100'],
            'billing_state' => ['required', 'string', 'max:100'],
            'billing_country' => ['required', 'string', 'max:100'],
            'billing_zip' => ['required', 'string', 'max:20'],
            'finger' => ['nullable', 'string', 'max:191'],
            'fingerprint_session_id' => ['required', 'string', 'max:191'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cc_number' => preg_replace('/\D+/', '', (string) $this->input('cc_number')),
            'cc_type' => strtolower((string) $this->input('cc_type')),
            'finger' => (string) $this->input('finger', ''),
        ]);
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['finger'] = $data['finger'] ?: 'unavailable';

        return $key ? data_get($data, $key, $default) : $data;
    }
}
