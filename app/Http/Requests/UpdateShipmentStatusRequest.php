<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isOperator());
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:CREATED,BOOKED,PICKED_UP,IN_TRANSIT,DELIVERED,CANCELLED'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status baru wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'note.max' => 'Catatan maksimal 500 karakter.',
        ];
    }
}
