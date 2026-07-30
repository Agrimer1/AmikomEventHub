<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePromoCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:promo_codes,code',
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
