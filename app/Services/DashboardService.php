<?php

namespace App\Services;

use App\Models\Carrier;
use App\Models\Notification;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Models\User;

class DashboardService
{
    /**
     * Dapatkan seluruh data analitik, metrik KPI, distribusi grafik, dan data ringkas untuk dashboard.
     *
     * @param User $user
     * @return array
     */
    public function getDashboardData(User $user): array
    {
        $isCustomer = $user->isCustomer();

        // 1. Query dasar shipment berdasarkan scope role pengguna
        $shipmentQuery = $isCustomer
            ? Shipment::where('customer_id', $user->id)
            : Shipment::query();

        // 2. Query dasar exception berdasarkan scope role pengguna
        $exceptionQuery = $isCustomer
            ? ShipmentException::whereHas('shipment', function ($q) use ($user) {
                $q->where('customer_id', $user->id);
            })
            : ShipmentException::query();

        // 3. Perhitungan KPI Utama (Langsung dari database)
        $totalShipments = (clone $shipmentQuery)->count();
        $inTransit = (clone $shipmentQuery)->where('status', 'IN_TRANSIT')->count();
        $delivered = (clone $shipmentQuery)->where('status', 'DELIVERED')->count();
        
        $delayedShipments = (clone $shipmentQuery)->where(function ($q) {
            $q->where('delay_hours', '>', 0)
              ->orWhere('delay_severity', '!=', 'NONE');
        })->count();

        $openExceptions = (clone $exceptionQuery)
            ->whereIn('status', ['OPEN', 'INVESTIGATING'])
            ->count();

        // Delay Rate (%) = (jumlah shipment delayed / total shipment) * 100
        $delayRate = $totalShipments > 0
            ? round(($delayedShipments / $totalShipments) * 100, 1)
            : 0.0;

        // Exception Rate (%) = (jumlah shipment unik yang pernah memiliki exception / total shipment) * 100
        $shipmentsWithExceptionCount = (clone $shipmentQuery)->has('exceptions')->count();
        $exceptionRate = $totalShipments > 0
            ? round(($shipmentsWithExceptionCount / $totalShipments) * 100, 1)
            : 0.0;

        $kpis = [
            'total_shipments' => $totalShipments,
            'in_transit' => $inTransit,
            'delivered' => $delivered,
            'delayed' => $delayedShipments,
            'open_exceptions' => $openExceptions,
            'delay_rate' => $delayRate,
            'exception_rate' => $exceptionRate,
        ];

        // 4. Data Distribusi Status Pengiriman (Untuk Chart.js Status)
        $definedStatuses = ['CREATED', 'BOOKED', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED', 'CANCELLED'];
        $rawStatusCounts = (clone $shipmentQuery)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statusDistribution = [];
        foreach ($definedStatuses as $st) {
            $statusDistribution[$st] = (int) ($rawStatusCounts[$st] ?? 0);
        }

        // 5. Data Distribusi Pengiriman per Carrier (Untuk Chart.js Carrier)
        $carrierDistribution = (clone $shipmentQuery)
            ->join('carriers', 'shipments.carrier_id', '=', 'carriers.id')
            ->selectRaw('carriers.name as carrier_name, count(shipments.id) as total')
            ->groupBy('carriers.id', 'carriers.name')
            ->orderByDesc('total')
            ->pluck('total', 'carrier_name')
            ->toArray();

        // 5b. Data Detail Carrier & Moda Distribusi untuk Interactive Control Tower Panel
        $carrierStats = (clone $shipmentQuery)
            ->join('carriers', 'shipments.carrier_id', '=', 'carriers.id')
            ->selectRaw('carriers.id, carriers.name, carriers.code, count(shipments.id) as total')
            ->groupBy('carriers.id', 'carriers.name', 'carriers.code')
            ->orderByDesc('total')
            ->get()
            ->map(function ($c) {
                return [
                    'name' => $c->name,
                    'code' => $c->code ?: strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $c->name), 0, 4)),
                    'total' => (int) $c->total,
                ];
            })
            ->toArray();

        $rawModeCounts = (clone $shipmentQuery)
            ->selectRaw('transport_mode, count(*) as count')
            ->groupBy('transport_mode')
            ->pluck('count', 'transport_mode')
            ->toArray();

        $modeDistribution = [
            'OCEAN' => (int) ($rawModeCounts['OCEAN'] ?? 0),
            'AIR' => (int) ($rawModeCounts['AIR'] ?? 0),
            'LAND' => (int) ($rawModeCounts['LAND'] ?? 0),
        ];

        $onTimeShipments = max(0, $totalShipments - $delayedShipments);
        $slaDistribution = [
            'ON_TIME' => $onTimeShipments,
            'DELAYED' => $delayedShipments,
        ];

        // 6. Data Tabel Ringkas Pengiriman Terbaru (5-6 data terbaru)
        $recentShipments = (clone $shipmentQuery)
            ->with(['customer', 'carrier', 'originLocation', 'destinationLocation'])
            ->latest()
            ->take(6)
            ->get();

        // 7. Panel Exception Aktif (Khusus Admin & Operator, atau null untuk Customer)
        $activeExceptions = collect();
        if (! $isCustomer) {
            $activeExceptions = ShipmentException::with(['shipment.carrier', 'shipment.customer', 'reporter'])
                ->whereIn('status', ['OPEN', 'INVESTIGATING'])
                ->latest('detected_at')
                ->take(5)
                ->get();
        }

        // 8. Notifikasi terbaru untuk pengguna
        $recentNotifications = Notification::where('user_id', $user->id)
            ->with('shipment')
            ->latest()
            ->take(5)
            ->get();

        $unreadNotificationsCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // 9. Data Peta Sebaran Pengiriman Aktif Berbasis Koordinat Geospasial Aktual
        $mapShipmentsQuery = $isCustomer
            ? Shipment::where('customer_id', $user->id)
            : Shipment::query();

        $rawMapShipments = $mapShipmentsQuery
            ->where('status', '!=', 'CANCELLED')
            ->with(['originLocation', 'destinationLocation', 'currentLocation', 'carrier'])
            ->get();

        $mapShipments = [];
        foreach ($rawMapShipments as $shipment) {
            $origin = $shipment->originLocation;
            $destination = $shipment->destinationLocation;

            // Pastikan setiap item hanya dimasukkan jika minimal originLocation dan destinationLocation memiliki koordinat valid
            if (! $origin || $origin->latitude === null || $origin->longitude === null) {
                continue;
            }

            if (! $destination || $destination->latitude === null || $destination->longitude === null) {
                continue;
            }

            // Jika currentLocation belum ada, gunakan koordinat originLocation sebagai posisi marker saat ini
            $current = $shipment->currentLocation;
            $hasValidCurrent = $current && $current->latitude !== null && $current->longitude !== null;

            $currentData = [
                'name' => $hasValidCurrent ? $current->name : $origin->name,
                'code' => $hasValidCurrent ? $current->code : $origin->code,
                'country' => $hasValidCurrent ? ($current->country ?? '') : ($origin->country ?? ''),
                'lat' => (float) ($hasValidCurrent ? $current->latitude : $origin->latitude),
                'lng' => (float) ($hasValidCurrent ? $current->longitude : $origin->longitude),
            ];

            $mapShipments[] = [
                'id' => $shipment->id,
                'tracking_number' => $shipment->tracking_number,
                'status' => $shipment->status,
                'transport_mode' => $shipment->transport_mode,
                'cargo_type' => $shipment->cargo_type,
                'carrier_name' => $shipment->carrier?->name ?? '-',
                'delay_severity' => $shipment->delay_severity ?? 'NONE',
                'delay_hours' => (int) $shipment->delay_hours,
                'origin' => [
                    'name' => $origin->name,
                    'code' => $origin->code,
                    'country' => $origin->country ?? '',
                    'lat' => (float) $origin->latitude,
                    'lng' => (float) $origin->longitude,
                ],
                'destination' => [
                    'name' => $destination->name,
                    'code' => $destination->code,
                    'country' => $destination->country ?? '',
                    'lat' => (float) $destination->latitude,
                    'lng' => (float) $destination->longitude,
                ],
                'current' => $currentData,
                'detail_url' => route('shipments.show', $shipment),
            ];
        }

        return [
            'kpis' => $kpis,
            'statusDistribution' => $statusDistribution,
            'carrierDistribution' => $carrierDistribution,
            'carrierStats' => $carrierStats,
            'modeDistribution' => $modeDistribution,
            'slaDistribution' => $slaDistribution,
            'recentShipments' => $recentShipments,
            'activeExceptions' => $activeExceptions,
            'recentNotifications' => $recentNotifications,
            'unreadNotificationsCount' => $unreadNotificationsCount,
            'mapShipments' => $mapShipments,
        ];
    }
}
