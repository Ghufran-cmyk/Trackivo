<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Services\EtaService;
use App\Services\TrackingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicTrackingController extends Controller
{
    public function __construct(
        protected TrackingService $trackingService,
        protected EtaService $etaService
    ) {}

    /**
     * Tampilkan halaman pelacakan resi pengiriman publik.
     * Tidak memerlukan autentikasi dan menjaga privasi data sensitif pelanggan.
     */
    public function index(Request $request): View
    {
        $rawTrackingNumber = (string) $request->query('tracking_number', '');
        $trackingNumber = trim($rawTrackingNumber);
        $searched = $trackingNumber !== '';
        $shipment = null;
        $mapData = null;
        $delayAnalysis = null;
        $currentLocation = null;
        $timelineEvents = collect();

        if ($searched) {
            $shipment = Shipment::query()
                ->whereRaw('LOWER(tracking_number) = ?', [strtolower($trackingNumber)])
                ->with([
                    'carrier:id,code,name',
                    'originLocation:id,code,name,country,type,latitude,longitude',
                    'destinationLocation:id,code,name,country,type,latitude,longitude',
                    'trackingEvents' => function ($query) {
                        $query->with('location:id,code,name,country,type,latitude,longitude')
                              ->orderBy('event_time', 'asc');
                    },
                ])
                ->first();

            if ($shipment) {
                // Sembunyikan catatan internal operator/admin untuk privasi publik
                $shipment->notes = null;

                // Evaluasi estimasi kedatangan dan keterlambatan via EtaService
                $delayAnalysis = $this->etaService->evaluateDelay($shipment);

                // Dapatkan posisi aktual terakhir
                $currentLocation = $this->trackingService->getCurrentLocation($shipment);

                // Timeline checkpoint publik: urutan kronologis terbaru di atas (descending)
                $timelineEvents = $shipment->trackingEvents->sortByDesc('event_time')->values();

                // Siapkan struktur payload data geospasial untuk peta Leaflet.js
                $originLoc = $shipment->originLocation;
                $destLoc = $shipment->destinationLocation;

                $mapData = [
                    'origin' => [
                        'name' => $originLoc?->name ?? 'Titik Asal (Origin)',
                        'code' => $originLoc?->code ?? 'ORG',
                        'type' => $originLoc?->type ?? 'PORT',
                        'latitude' => $originLoc?->latitude !== null ? (float) $originLoc->latitude : null,
                        'longitude' => $originLoc?->longitude !== null ? (float) $originLoc->longitude : null,
                        'expected_departure' => $shipment->expected_departure
                            ? $shipment->expected_departure->format('d M Y, H:i') . ' WIB'
                            : null,
                    ],
                    'destination' => [
                        'name' => $destLoc?->name ?? 'Titik Tujuan (Destination)',
                        'code' => $destLoc?->code ?? 'DST',
                        'type' => $destLoc?->type ?? 'PORT',
                        'latitude' => $destLoc?->latitude !== null ? (float) $destLoc->latitude : null,
                        'longitude' => $destLoc?->longitude !== null ? (float) $destLoc->longitude : null,
                        'expected_arrival' => $shipment->expected_arrival
                            ? $shipment->expected_arrival->format('d M Y, H:i') . ' WIB'
                            : null,
                        'estimated_arrival' => $shipment->estimated_arrival
                            ? $shipment->estimated_arrival->format('d M Y, H:i') . ' WIB'
                            : null,
                    ],
                    'events' => [],
                ];

                foreach ($shipment->trackingEvents as $event) {
                    $lat = $event->latitude ?? $event->location?->latitude;
                    $lng = $event->longitude ?? $event->location?->longitude;

                    if ($lat !== null && $lng !== null) {
                        $mapData['events'][] = [
                            'id' => $event->id,
                            'status' => $event->status,
                            'is_exception' => (bool) $event->is_exception,
                            'latitude' => (float) $lat,
                            'longitude' => (float) $lng,
                            'location_name' => $event->location?->name ?? 'Posisi Lapangan / Checkpoint',
                            'location_code' => $event->location?->code ?? 'GPS',
                            'event_time' => $event->event_time
                                ? Carbon::parse($event->event_time)->format('d M Y, H:i') . ' WIB'
                                : '-',
                            'description' => $event->description ?? '',
                        ];
                    }
                }
            }
        }

        return view('tracking.public', [
            'trackingNumber' => $trackingNumber,
            'searched' => $searched,
            'shipment' => $shipment,
            'mapData' => $mapData,
            'delayAnalysis' => $delayAnalysis,
            'currentLocation' => $currentLocation,
            'timelineEvents' => $timelineEvents,
        ]);
    }
}
