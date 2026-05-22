<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class QPayProCallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'x_invoice_num' => ['nullable', 'string', 'max:191'],
            'status' => ['nullable', 'string', 'max:100'],
            'response_code' => ['nullable', 'string', 'max:100'],
            'transaction_id' => ['nullable', 'string', 'max:191'],
        ];
    }
}
