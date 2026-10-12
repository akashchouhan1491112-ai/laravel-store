<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isVendor();
    }

    public function rules(): array
    {
        $storeId = $this->user()->store ? $this->user()->store->id : null;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:stores,name,' . $storeId],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'swift_code' => ['nullable', 'string', 'max:20'],
        ];
    }
}
