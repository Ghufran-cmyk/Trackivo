<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportExceptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOperator());
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'in:CUSTOMS_HOLD,WEATHER_DELAY,PORT_CONGESTION,DAMAGED,MISROUTED,DOCUMENTATION_ISSUE',
            ],
            'severity' => ['required', 'in:LOW,MEDIUM,HIGH'],
            'delay_impact_hours' => ['nullable', 'integer', 'min:0', 'max:720'],
            'description' => ['required', 'string', 'max:2000'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'detected_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Jenis kendala / exception wajib dipilih.',
            'type.in' => 'Jenis kendala yang dipilih tidak valid.',
            'severity.required' => 'Tingkat keparahan (severity) wajib dipilih.',
            'severity.in' => 'Tingkat keparahan yang dipilih tidak valid.',
            'delay_impact_hours.integer' => 'Estimasi dampak delay harus berupa angka bulat dalam satuan jam.',
            'delay_impact_hours.min' => 'Estimasi dampak delay tidak boleh bernilai negatif.',
            'description.required' => 'Deskripsi rincian kendala wajib diisi.',
            'location_id.exists' => 'Lokasi kendala yang dipilih tidak valid dalam database.',
        ];
    }
}
