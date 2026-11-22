<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Shipment;
use App\Models\TrackingEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrackingService
{
    public function __construct(
        protected ShipmentService $shipmentService
    ) {}

    /**
     * Tambah tracking event baru tanpa menimpa event historis lama.
     */
    public function addTrackingEvent(Shipment $shipment, array $data, User $actor): TrackingEvent
    {
        // Tolak penambahan event jika status shipment sudah terminal (DELIVERED atau CANCELLED)
        if (in_array($shipment->status, ['DELIVERED', 'CANCELLED'], true)) {
            throw ValidationException::withMessages([
                'event' => "Tidak dapat menambahkan tracking event karena pengiriman sudah berstatus final '{$shipment->status}'.",
            ]);
        }

        // Parse waktu event
        $eventTime = ! empty($data['event_time'])
            ? Carbon::parse($data['event_time'])
            : now();

        // Validasi urutan waktu kronologis: tidak boleh lebih awal dari tracking event terakhir
        $latestEvent = $shipment->trackingEvents()->orderByDesc('event_time')->first();
        if ($latestEvent && $eventTime->lt($latestEvent->event_time)) {
            throw ValidationException::withMessages([
                'event_time' => "Waktu event ({$eventTime->format('d/m/Y H:i')}) tidak boleh lebih awal dari tracking event terakhir ({$latestEvent->event_time->format('d/m/Y H:i')}).",
            ]);
        }

        // Tentukan koordinat: jika location_id dipilih dan koordinat manual kosong, ambil dari master Location
        $latitude = $data['latitude'] ?? null;
        $longitude = $data['longitude'] ?? null;
        $locationId = ! empty($data['location_id']) ? (int) $data['location_id'] : null;

        if ($locationId) {
            $location = Location::find($locationId);
            if ($location) {
                if (empty($latitude) && $location->latitude !== null) {
                    $latitude = $location->latitude;
                }
                if (empty($longitude) && $location->longitude !== null) {
                    $longitude = $location->longitude;
                }
            }
        }

        // Cek status baru jika ada perubahan status
        $newStatus = ! empty($data['status']) ? $data['status'] : $shipment->status;

        if ($newStatus !== $shipment->status) {
            if (! $this->shipmentService->canTransition($shipment->status, $newStatus)) {
                $allowed = $this->shipmentService->getAllowedNextStatuses($shipment->status);
                $allowedText = ! empty($allowed) ? implode(', ', $allowed) : 'Tidak ada (status final)';

                throw ValidationException::withMessages([
                    'status' => "Transisi status dari '{$shipment->status}' ke '{$newStatus}' tidak diperbolehkan. Status selanjutnya yang sah: {$allowedText}.",
                ]);
            }
        }

        return DB::transaction(function () use ($shipment, $data, $actor, $eventTime, $latitude, $longitude, $locationId, $newStatus) {
            // Update status shipment jika terjadi transisi status
            if ($newStatus !== $shipment->status) {
                $shipment->status = $newStatus;
                $shipment->save();
            }

            // Simpan TrackingEvent baru (is_exception = false)
            $event = TrackingEvent::create([
                'shipment_id' => $shipment->id,
                'location_id' => $locationId,
                'status' => $newStatus,
                'is_exception' => false,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'event_time' => $eventTime,
                'description' => $data['description'] ?? "Checkpoint kargo tercatat pada status {$newStatus}.",
                'created_by' => $actor->id,
            ]);

            // In-app Notification untuk customer pemilik shipment
            if ($shipment->customer_id) {
                Notification::create([
                    'user_id' => $shipment->customer_id,
                    'shipment_id' => $shipment->id,
                    'title' => "Update Checkpoint: {$shipment->tracking_number}",
                    'message' => "Checkpoint baru tercatat untuk pengiriman {$shipment->tracking_number} [{$newStatus}]: " . ($data['description'] ?? 'Pembaruan lokasi pengiriman.'),
                    'is_read' => false,
                    'read_at' => null,
                ]);
            }

            // Catat ke audit_logs
            AuditLog::create([
                'user_id' => $actor->id,
                'action' => 'ADD_TRACKING_EVENT',
                'target_type' => TrackingEvent::class,
                'target_id' => $event->id,
                'old_values' => null,
                'new_values' => $event->toArray(),
                'ip_address' => request()->ip(),
            ]);

            return $event->load('location', 'creator');
        });
    }

    /**
     * Dapatkan lokasi terkini dari tracking event terbaru yang valid (memiliki relasi location atau koordinat).
     */
    public function getCurrentLocation(Shipment $shipment): ?array
    {
        // Cari event terbaru yang memiliki lokasi atau koordinat valid
        $events = $shipment->trackingEvents()
            ->with('location')
            ->orderByDesc('event_time')
            ->get();

        $latestEvent = $events->first(function ($event) {
            return $event->location !== null || ($event->latitude !== null && $event->longitude !== null);
        });

        if (! $latestEvent) {
            // Fallback ke origin location shipment jika belum ada checkpoint
            $origin = $shipment->originLocation;
            if ($origin) {
                return [
                    'location_name' => $origin->name,
                    'location_code' => $origin->code,
                    'country' => $origin->country,
                    'type' => $origin->type,
                    'latitude' => $origin->latitude,
                    'longitude' => $origin->longitude,
                    'event_time' => $shipment->created_at,
                    'status' => $shipment->status,
                    'description' => 'Titik asal muatan kargo (Origin Hub).',
                    'is_fallback' => true,
                ];
            }

            return null;
        }

        $locationName = $latestEvent->location ? $latestEvent->location->name : 'Posisi Lapangan / Koordinat Geospasial';
        $locationCode = $latestEvent->location ? $latestEvent->location->code : 'GPS-FIX';
        $country = $latestEvent->location ? $latestEvent->location->country : 'International Waters / Transit';
        $type = $latestEvent->location ? $latestEvent->location->type : 'CHECKPOINT';

        return [
            'location_name' => $locationName,
            'location_code' => $locationCode,
            'country' => $country,
            'type' => $type,
            'latitude' => $latestEvent->latitude ?? $latestEvent->location?->latitude,
            'longitude' => $latestEvent->longitude ?? $latestEvent->location?->longitude,
            'event_time' => $latestEvent->event_time,
            'status' => $latestEvent->status,
            'description' => $latestEvent->description,
            'is_fallback' => false,
        ];
    }
}
