<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class PayoutWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasApprovedStore();
    }

    public function rules(): array
    {
        $maxBalance = $this->user()->store ? $this->user()->store->balance : 0;

        return [
            'amount' => ['required', 'numeric', 'min:20', 'max:' . $maxBalance],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
