<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePromoCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $promoCode = $this->route('promo_code');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('promo_codes', 'code')->ignore($promoCode),
            ],
            'type' => 'required|in:fixed,percentage',
            'discount_amount' => 'required|numeric|min:0',
            'min_transaction' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'expired_at' => 'nullable|date',
        ];
    }
}
