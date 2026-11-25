<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isOperator());
    }

    public function rules(): array
    {
        return [
            'tracking_number' => ['nullable', 'string', 'max:50', 'unique:shipments,tracking_number'],
            'customer_id' => ['required', 'exists:users,id'],
            'carrier_id' => ['required', 'exists:carriers,id'],
            'origin_location_id' => ['required', 'exists:locations,id', 'different:destination_location_id'],
            'destination_location_id' => ['required', 'exists:locations,id', 'different:origin_location_id'],
            'transport_mode' => ['required', 'in:OCEAN,AIR,LAND'],
            'cargo_type' => ['required', 'string', 'max:255'],
            'weight_kg' => ['required', 'numeric', 'min:0.01'],
            'volume_cbm' => ['nullable', 'numeric', 'min:0.01'],
            'insurance_cost' => ['nullable', 'integer', 'min:0'],
            'payment_method' => ['nullable', 'string', 'in:CASH,BANK_TRANSFER,QRIS,CORPORATE_INVOICE'],
            'payment_status' => ['nullable', 'string', 'in:UNPAID,PAID'],
            'expected_departure' => ['required', 'date'],
            'expected_arrival' => ['required', 'date', 'after:expected_departure'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.weight_kg' => ['required', 'numeric', 'min:0.01'],
            'items.*.description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pelanggan (Customer) wajib dipilih.',
            'customer_id.exists' => 'Pelanggan yang dipilih tidak valid.',
            'carrier_id.required' => 'Carrier pengangkut wajib dipilih.',
            'carrier_id.exists' => 'Carrier yang dipilih tidak terdaftar.',
            'origin_location_id.required' => 'Lokasi asal pengiriman wajib dipilih.',
            'origin_location_id.different' => 'Lokasi asal tidak boleh sama dengan lokasi tujuan.',
            'destination_location_id.required' => 'Lokasi tujuan pengiriman wajib dipilih.',
            'destination_location_id.different' => 'Lokasi tujuan tidak boleh sama dengan lokasi asal.',
            'transport_mode.required' => 'Moda transportasi wajib dipilih.',
            'transport_mode.in' => 'Moda transportasi harus OCEAN, AIR, atau LAND.',
            'cargo_type.required' => 'Jenis kargo wajib diisi.',
            'weight_kg.required' => 'Berat total kargo wajib diisi.',
            'weight_kg.min' => 'Berat kargo minimal 0.01 kg.',
            'expected_departure.required' => 'Jadwal keberangkatan wajib diisi.',
            'expected_arrival.required' => 'Estimasi kedatangan wajib diisi.',
            'expected_arrival.after' => 'Estimasi kedatangan harus setelah jadwal keberangkatan.',
            'items.required' => 'Minimal harus menambahkan 1 item muatan kargo.',
            'items.min' => 'Minimal harus menambahkan 1 item muatan kargo.',
            'items.*.item_name.required' => 'Nama item kargo wajib diisi.',
            'items.*.quantity.required' => 'Jumlah kuantitas item wajib diisi.',
            'items.*.quantity.min' => 'Jumlah kuantitas item minimal 1.',
            'items.*.weight_kg.required' => 'Berat item wajib diisi.',
            'items.*.weight_kg.min' => 'Berat item minimal 0.01 kg.',
        ];
    }
}
