<?php

namespace App\Services;

class ShippingRateService
{
    /**
     * Tarif per Moda:
     * - OCEAN: Dasar Rp 750.000 + Rp 1.500/kg + Rp 250.000/CBM
     * - AIR:   Dasar Rp 1.500.000 + Rp 8.500/kg + Rp 600.000/CBM
     * - LAND:  Dasar Rp 350.000 + Rp 2.000/kg + Rp 150.000/CBM
     */
    public const RATES = [
        'OCEAN' => [
            'base' => 750000,
            'rate_kg' => 1500,
            'rate_cbm' => 250000,
        ],
        'AIR' => [
            'base' => 1500000,
            'rate_kg' => 8500,
            'rate_cbm' => 600000,
        ],
        'LAND' => [
            'base' => 350000,
            'rate_kg' => 2000,
            'rate_cbm' => 150000,
        ],
    ];

    public const PAYMENT_METHODS = [
        'CASH' => 'Tunai (Loket)',
        'BANK_TRANSFER' => 'Transfer Bank',
        'QRIS' => 'QRIS',
        'CORPORATE_INVOICE' => 'Tagihan Invoice B2B',
    ];

    /**
     * Hitung rincian biaya pengiriman.
     *
     * @param string $mode Moda transportasi: OCEAN, AIR, atau LAND
     * @param float $weightKg Berat kargo dalam kg
     * @param float $volumeCbm Volume kargo dalam CBM
     * @param int $insuranceCost Biaya layanan & asuransi opsional
     * @return array{base_cost: int, weight_volume_cost: int, insurance_cost: int, total_amount: int}
     */
    public static function calculate(string $mode, float $weightKg, float $volumeCbm = 0.0, int $insuranceCost = 0): array
    {
        $modeKey = strtoupper($mode);
        $rate = self::RATES[$modeKey] ?? self::RATES['LAND'];

        $baseCost = (int) $rate['base'];
        $weightCost = $weightKg * $rate['rate_kg'];
        $volumeCost = $volumeCbm * $rate['rate_cbm'];

        $weightVolumeCost = (int) round($weightCost + $volumeCost);
        $insurance = max(0, $insuranceCost);
        $totalAmount = $baseCost + $weightVolumeCost + $insurance;

        return [
            'base_cost' => $baseCost,
            'weight_volume_cost' => $weightVolumeCost,
            'insurance_cost' => $insurance,
            'total_amount' => $totalAmount,
        ];
    }

    /**
     * Format nominal integer Rupiah menjadi string rapi (contoh: Rp 1.500.000).
     */
    public static function formatRupiah(int|float|null $amount): string
    {
        $val = (int) ($amount ?? 0);
        return 'Rp ' . number_format($val, 0, ',', '.');
    }

    /**
     * Dapatkan label representatif dari kode metode pembayaran.
     */
    public static function getPaymentMethodLabel(?string $method): string
    {
        if (! $method) {
            return '-';
        }

        return self::PAYMENT_METHODS[$method] ?? $method;
    }

    /**
     * Dapatkan daftar semua metode pembayaran yang valid beserta labelnya.
     */
    public static function getPaymentMethods(): array
    {
        return self::PAYMENT_METHODS;
    }
}
