<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:locations,code'],
            'name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:PORT,AIRPORT,WAREHOUSE,CHECKPOINT'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->code)),
        ]);
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode lokasi wajib diisi.',
            'code.unique' => 'Kode lokasi ini sudah terdaftar.',
            'name.required' => 'Nama lokasi wajib diisi.',
            'country.required' => 'Negara wajib diisi.',
            'type.required' => 'Tipe lokasi wajib dipilih.',
            'type.in' => 'Tipe lokasi harus salah satu dari: PORT, AIRPORT, WAREHOUSE, CHECKPOINT.',
            'latitude.required' => 'Latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'latitude.between' => 'Latitude harus berada dalam rentang -90 hingga 90 derajat.',
            'longitude.required' => 'Longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'longitude.between' => 'Longitude harus berada dalam rentang -180 hingga 180 derajat.',
        ];
    }
}
