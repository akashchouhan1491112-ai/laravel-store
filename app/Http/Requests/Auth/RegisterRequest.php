<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(6)],
            'role' => ['nullable', 'string', 'in:customer,vendor'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            // Vendor registration optional store fields
            'store_name' => ['required_if:role,vendor', 'nullable', 'string', 'max:255', 'unique:stores,name'],
            'store_description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
