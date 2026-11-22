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

        return [
            'kpis' => $kpis,
            'statusDistribution' => $statusDistribution,
            'carrierDistribution' => $carrierDistribution,
            'recentShipments' => $recentShipments,
            'activeExceptions' => $activeExceptions,
            'recentNotifications' => $recentNotifications,
            'unreadNotificationsCount' => $unreadNotificationsCount,
        ];
    }
}
