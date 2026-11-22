<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExceptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOperator());
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:INVESTIGATING,RESOLVED'],
            'resolution_note' => ['required_if:status,RESOLVED', 'nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status baru kendala wajib dipilih.',
            'status.in' => 'Status baru yang dipilih harus INVESTIGATING atau RESOLVED.',
            'resolution_note.required_if' => 'Catatan penyelesaian (resolution note) wajib diisi jika status diubah menjadi RESOLVED.',
        ];
    }
}
