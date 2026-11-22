<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackingEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOperator());
    }

    public function rules(): array
    {
        return [
            'location_id' => ['nullable', 'exists:locations,id'],
            'status' => ['nullable', 'string', 'in:CREATED,BOOKED,PICKED_UP,IN_TRANSIT,DELIVERED,CANCELLED'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'event_time' => ['nullable', 'date'],
            'description' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'Deskripsi checkpoint perjalanan kargo wajib diisi.',
            'location_id.exists' => 'Lokasi yang dipilih tidak valid dalam sistem.',
            'latitude.between' => 'Koordinat garis lintang (latitude) harus berada di antara -90 dan 90.',
            'longitude.between' => 'Koordinat garis bujur (longitude) harus berada di antara -180 dan 180.',
            'status.in' => 'Status pengiriman yang dipilih tidak valid.',
        ];
    }
}
