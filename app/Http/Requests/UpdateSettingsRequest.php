<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'delay_low_max_hours' => ['required', 'integer', 'min:1', 'max:168'],
            'delay_high_min_hours' => ['required', 'integer', 'gt:delay_low_max_hours', 'max:720'],
        ];
    }

    public function messages(): array
    {
        return [
            'delay_low_max_hours.required' => 'Batas maksimal delay kategori LOW wajib diisi.',
            'delay_low_max_hours.integer' => 'Batas maksimal delay LOW harus berupa bilangan bulat positif.',
            'delay_low_max_hours.min' => 'Batas maksimal delay LOW minimal bernilai 1 jam.',
            'delay_high_min_hours.required' => 'Batas minimal delay kategori HIGH wajib diisi.',
            'delay_high_min_hours.integer' => 'Batas minimal delay HIGH harus berupa bilangan bulat positif.',
            'delay_high_min_hours.gt' => 'Batas minimal delay HIGH harus bernilai lebih besar dari batas maksimal delay LOW.',
        ];
    }
}
