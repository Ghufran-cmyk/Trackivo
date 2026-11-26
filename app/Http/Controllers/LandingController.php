<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use Illuminate\View\View;
use Throwable;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page resmi Trackivo bergaya Editorial Enterprise.
     */
    public function __invoke(): View
    {
        $totalShipments = 1248;
        $totalLocations = 42;
        $totalCarriers = 18;
        $onTimePercentage = 95;
        $featuredShipments = collect();
        $sampleTrackingNumber = 'TRK-2026-JKT-RTM-001';

        try {
            $shipmentCount = Shipment::count();
            $locationCount = Location::count();
            $carrierCount = Carrier::count();

            $totalShipments = $shipmentCount > 0 ? $shipmentCount : 1248;
            $totalLocations = $locationCount > 0 ? $locationCount : 42;
            $totalCarriers = $carrierCount > 0 ? $carrierCount : 18;

            if ($shipmentCount > 0) {
                $onTimeCount = Shipment::where('delay_hours', '<=', 0)->count();
                $onTimePercentage = (int) round(($onTimeCount / $shipmentCount) * 100);
            }

            $featured = Shipment::with([
                'originLocation:id,code,name,country',
                'destinationLocation:id,code,name,country',
                'carrier:id,code,name',
            ])
            ->orderBy('id', 'asc')
            ->take(4)
            ->get();

            if ($featured->isNotEmpty()) {
                $featuredShipments = $featured;
                $sampleTrackingNumber = $featured->first()->tracking_number;
            }
        } catch (Throwable) {
            // Gunakan fallback jika tabel belum siap atau query gagal
        }

        if ($featuredShipments->isEmpty()) {
            $featuredShipments = collect([
                (object) [
                    'id' => 1,
                    'tracking_number' => 'TRK-2026-JKT-RTM-001',
                    'transport_mode' => 'OCEAN',
                    'status' => 'IN_TRANSIT',
                    'cargo_type' => 'Tekstil & Garmen Ekspor',
                    'delay_hours' => 0,
                    'estimated_arrival' => now()->addDays(5)->format('Y-m-d H:i:s'),
                    'originLocation' => (object) ['name' => 'Tanjung Priok, Jakarta', 'code' => 'JKT', 'country' => 'Indonesia'],
                    'destinationLocation' => (object) ['name' => 'Port of Rotterdam', 'code' => 'RTM', 'country' => 'Belanda'],
                    'carrier' => (object) ['name' => 'Maersk Line', 'code' => 'MAEU'],
                ],
                (object) [
                    'id' => 2,
                    'tracking_number' => 'TRK-2026-CGK-AMS-002',
                    'transport_mode' => 'AIR',
                    'status' => 'IN_TRANSIT',
                    'cargo_type' => 'Komponen Semikonduktor & Medis',
                    'delay_hours' => 4,
                    'estimated_arrival' => now()->addDays(2)->format('Y-m-d H:i:s'),
                    'originLocation' => (object) ['name' => 'Soekarno-Hatta (CGK)', 'code' => 'CGK', 'country' => 'Indonesia'],
                    'destinationLocation' => (object) ['name' => 'Amsterdam Schiphol (AMS)', 'code' => 'AMS', 'country' => 'Belanda'],
                    'carrier' => (object) ['name' => 'Garuda Indonesia Cargo', 'code' => 'GIA'],
                ],
                (object) [
                    'id' => 3,
                    'tracking_number' => 'TRK-2026-JKT-SBY-003',
                    'transport_mode' => 'LAND',
                    'status' => 'DELIVERED',
                    'cargo_type' => 'Suku Cadang Otomotif Presisi',
                    'delay_hours' => 0,
                    'estimated_arrival' => now()->addDays(1)->format('Y-m-d H:i:s'),
                    'originLocation' => (object) ['name' => 'Hub Cikarang, Bekasi', 'code' => 'CKR', 'country' => 'Indonesia'],
                    'destinationLocation' => (object) ['name' => 'Perak Logistics Hub, Surabaya', 'code' => 'SUB', 'country' => 'Indonesia'],
                    'carrier' => (object) ['name' => 'KALOG Multimodal', 'code' => 'KLOG'],
                ],
                (object) [
                    'id' => 4,
                    'tracking_number' => 'TRK-2026-JKT-LAX-004',
                    'transport_mode' => 'OCEAN',
                    'status' => 'PICKED_UP',
                    'cargo_type' => 'Furnitur & Kerajinan Kayu',
                    'delay_hours' => 0,
                    'estimated_arrival' => now()->addDays(7)->format('Y-m-d H:i:s'),
                    'originLocation' => (object) ['name' => 'Tanjung Perak, Surabaya', 'code' => 'SUB', 'country' => 'Indonesia'],
                    'destinationLocation' => (object) ['name' => 'Port of Los Angeles (LAX)', 'code' => 'LAX', 'country' => 'Amerika Serikat'],
                    'carrier' => (object) ['name' => 'MSC Mediterranean', 'code' => 'MSCU'],
                ],
            ]);
            $sampleTrackingNumber = 'TRK-2026-JKT-RTM-001';
        }

        return view('landing', compact(
            'totalShipments',
            'totalLocations',
            'totalCarriers',
            'onTimePercentage',
            'featuredShipments',
            'sampleTrackingNumber'
        ));
    }
}
