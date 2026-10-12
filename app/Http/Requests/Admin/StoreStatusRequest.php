<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,approved,suspended,rejected'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
