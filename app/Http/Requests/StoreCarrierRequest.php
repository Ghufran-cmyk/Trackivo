<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarrierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:carriers,code'],
            'name' => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->code)),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode carrier wajib diisi.',
            'code.unique' => 'Kode carrier ini sudah terdaftar.',
            'name.required' => 'Nama carrier wajib diisi.',
            'contact_email.email' => 'Format email kontak tidak valid.',
        ];
    }
}
