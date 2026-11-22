<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Shipment;

class EtaService
{
    /**
     * Dapatkan batas konfigurasi delay dari tabel settings.
     */
    public function getThresholds(): array
    {
        $lowMax = (int) Setting::get('delay_low_max_hours', '6');
        $highMin = (int) Setting::get('delay_high_min_hours', '24');

        // Fallback jika tidak valid
        if ($lowMax <= 0) {
            $lowMax = 6;
        }
        if ($highMin <= $lowMax) {
            $highMin = 24;
        }

        return [
            'low_max_hours' => $lowMax,
            'high_min_hours' => $highMin,
        ];
    }

    /**
     * Evaluasi keterlambatan shipment secara deterministik berbasis konfigurasi settings.
     */
    public function evaluateDelay(Shipment $shipment): array
    {
        $thresholds = $this->getThresholds();
        $lowMax = $thresholds['low_max_hours'];
        $highMin = $thresholds['high_min_hours'];

        $expected = $shipment->expected_arrival;
        $estimated = $shipment->estimated_arrival;

        // Jika salah satu tanggal belum terdefinisi
        if (! $expected || ! $estimated) {
            return [
                'is_delayed' => false,
                'delay_hours' => 0,
                'delay_severity' => 'NONE',
                'low_max_hours' => $lowMax,
                'high_min_hours' => $highMin,
                'reason' => 'Data jadwal kedatangan (expected atau estimated arrival) belum lengkap.',
            ];
        }

        // Jika estimated_arrival <= expected_arrival: Tepat Waktu
        if ($estimated->lte($expected)) {
            $expectedStr = $expected->format('d/m/Y H:i');
            $estimatedStr = $estimated->format('d/m/Y H:i');

            return [
                'is_delayed' => false,
                'delay_hours' => 0,
                'delay_severity' => 'NONE',
                'low_max_hours' => $lowMax,
                'high_min_hours' => $highMin,
                'reason' => "Estimasi tiba ({$estimatedStr}) tepat waktu atau lebih awal dibandingkan jadwal yang diharapkan ({$expectedStr}). Tidak ada keterlambatan terdeteksi.",
            ];
        }

        // Jika estimated_arrival > expected_arrival: Terlambat
        // Hitung selisih jam (pembulatan ke atas / ceil dalam jam penuh minimal 1 jam)
        $diffMinutes = $expected->diffInMinutes($estimated);
        $delayHours = max(1, (int) ceil($diffMinutes / 60));

        if ($delayHours <= $lowMax) {
            $severity = 'LOW';
        } elseif ($delayHours <= $highMin) {
            $severity = 'MEDIUM';
        } else {
            $severity = 'HIGH';
        }

        $expectedStr = $expected->format('d/m/Y H:i');
        $estimatedStr = $estimated->format('d/m/Y H:i');

        $reason = "Estimasi tiba ({$estimatedStr}) melebihi target ({$expectedStr}) dengan selisih {$delayHours} jam. Berdasarkan batas konfigurasi aktif (LOW: ≤ {$lowMax} jam, MEDIUM: > {$lowMax}–{$highMin} jam, HIGH: > {$highMin} jam), keparahan delay ditetapkan sebagai {$severity}.";

        return [
            'is_delayed' => true,
            'delay_hours' => $delayHours,
            'delay_severity' => $severity,
            'low_max_hours' => $lowMax,
            'high_min_hours' => $highMin,
            'reason' => $reason,
        ];
    }

    /**
     * Evaluasi dan simpan nilai delay_hours dan delay_severity ke tabel shipments.
     */
    public function syncShipmentDelay(Shipment $shipment): Shipment
    {
        $evaluation = $this->evaluateDelay($shipment);

        $shipment->delay_hours = $evaluation['delay_hours'];
        $shipment->delay_severity = $evaluation['delay_severity'];
        $shipment->save();

        return $shipment->fresh();
    }

    /**
     * Hitung ulang seluruh shipment yang statusnya bukan DELIVERED dan bukan CANCELLED.
     */
    public function recalculateActiveShipments(): int
    {
        $activeShipments = Shipment::whereNotIn('status', ['DELIVERED', 'CANCELLED'])->get();
        $count = 0;

        foreach ($activeShipments as $shipment) {
            $this->syncShipmentDelay($shipment);
            $count++;
        }

        return $count;
    }
}
