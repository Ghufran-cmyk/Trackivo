<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Models\TrackingEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExceptionService
{
    public const VALID_TYPES = [
        'CUSTOMS_HOLD',
        'WEATHER_DELAY',
        'PORT_CONGESTION',
        'DAMAGED',
        'MISROUTED',
        'DOCUMENTATION_ISSUE',
    ];

    public const VALID_SEVERITIES = [
        'LOW',
        'MEDIUM',
        'HIGH',
    ];

    public const VALID_STATUSES = [
        'OPEN',
        'INVESTIGATING',
        'RESOLVED',
    ];

    public function __construct(
        protected EtaService $etaService
    ) {}

    /**
     * Laporkan kendala / exception baru pada pengiriman kargo.
     */
    public function reportException(Shipment $shipment, array $data, User $actor): ShipmentException
    {
        // Tolak jika status shipment sudah final
        if (in_array($shipment->status, ['DELIVERED', 'CANCELLED'], true)) {
            throw ValidationException::withMessages([
                'exception' => "Tidak dapat melaporkan kendala karena pengiriman sudah berstatus '{$shipment->status}'.",
            ]);
        }

        return DB::transaction(function () use ($shipment, $data, $actor) {
            $detectedAt = ! empty($data['detected_at'])
                ? Carbon::parse($data['detected_at'])
                : now();

            $delayImpactHours = max(0, (int) ($data['delay_impact_hours'] ?? 0));

            // 1. Simpan ShipmentException
            $exception = ShipmentException::create([
                'shipment_id' => $shipment->id,
                'type' => $data['type'],
                'severity' => $data['severity'],
                'status' => 'OPEN',
                'delay_impact_hours' => $delayImpactHours,
                'description' => $data['description'],
                'resolution_note' => null,
                'detected_at' => $detectedAt,
                'reported_by' => $actor->id,
            ]);

            // 2. Jika delay_impact_hours > 0, tambahkan jam tersebut ke shipment->estimated_arrival
            // lalu panggil EtaService::syncShipmentDelay($shipment)
            if ($delayImpactHours > 0) {
                if ($shipment->estimated_arrival) {
                    $shipment->estimated_arrival = $shipment->estimated_arrival->copy()->addHours($delayImpactHours);
                } elseif ($shipment->expected_arrival) {
                    $shipment->estimated_arrival = $shipment->expected_arrival->copy()->addHours($delayImpactHours);
                } else {
                    $shipment->estimated_arrival = now()->addHours($delayImpactHours);
                }
                $shipment->save();

                // Evaluasi dan sinkronkan keterlambatan deterministik
                $this->etaService->syncShipmentDelay($shipment);
            }

            // 3. Tentukan koordinat tracking event exception (opsional dari location_id jika ada)
            $locationId = ! empty($data['location_id']) ? (int) $data['location_id'] : null;
            $latitude = $data['latitude'] ?? null;
            $longitude = $data['longitude'] ?? null;

            if ($locationId) {
                $loc = Location::find($locationId);
                if ($loc) {
                    $latitude = $latitude ?? $loc->latitude;
                    $longitude = $longitude ?? $loc->longitude;
                }
            }

            // 4. Tambahkan 1 record ke tracking_events dengan is_exception = true
            // event_time = now() (atau 1 menit setelah event terakhir jika bentrok)
            $latestEvent = $shipment->trackingEvents()->orderByDesc('event_time')->first();
            $eventTime = now();
            if ($latestEvent && $latestEvent->event_time->gte($eventTime)) {
                $eventTime = $latestEvent->event_time->copy()->addMinute();
            }

            $trackingEvent = TrackingEvent::create([
                'shipment_id' => $shipment->id,
                'location_id' => $locationId,
                'status' => 'EXCEPTION: ' . $exception->type,
                'is_exception' => true,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'event_time' => $eventTime,
                'description' => "Kendala Operasional [{$exception->type} - {$exception->severity}]: {$exception->description}" . ($delayImpactHours > 0 ? " (Dampak keterlambatan estimasi: +{$delayImpactHours} jam)" : ''),
                'created_by' => $actor->id,
            ]);

            // 5. In-app Notification untuk customer pemilik shipment
            if ($shipment->customer_id) {
                $delayText = $delayImpactHours > 0 ? " Dampak penambahan waktu estimasi: +{$delayImpactHours} jam." : '';
                Notification::create([
                    'user_id' => $shipment->customer_id,
                    'shipment_id' => $shipment->id,
                    'title' => "Peringatan Kendala: {$shipment->tracking_number}",
                    'message' => "Kendala operasional '{$exception->type}' (Keparahan: {$exception->severity}) dilaporkan pada pengiriman {$shipment->tracking_number}.{$delayText} Rincian: {$exception->description}",
                    'is_read' => false,
                    'read_at' => null,
                ]);
            }

            // 6. Catat ke audit_logs
            AuditLog::create([
                'user_id' => $actor->id,
                'action' => 'REPORT_EXCEPTION',
                'target_type' => ShipmentException::class,
                'target_id' => $exception->id,
                'old_values' => null,
                'new_values' => $exception->toArray(),
                'ip_address' => request()->ip(),
            ]);

            return $exception->fresh(['shipment', 'reporter']);
        });
    }

    /**
     * Perbarui status kendala (investigasi atau penyelesaian/resolution).
     */
    public function updateException(ShipmentException $exception, array $data, User $actor): ShipmentException
    {
        $currentStatus = $exception->status;
        $newStatus = $data['status'] ?? $currentStatus;

        // Jika sudah RESOLVED tidak boleh diubah kembali
        if ($currentStatus === 'RESOLVED') {
            throw ValidationException::withMessages([
                'status' => 'Kendala (exception) yang sudah berstatus RESOLVED sudah bersifat final dan tidak dapat diubah kembali.',
            ]);
        }

        // Validasi transisi status yang sah:
        // OPEN -> INVESTIGATING atau RESOLVED
        // INVESTIGATING -> RESOLVED
        $allowedTransitions = [
            'OPEN' => ['INVESTIGATING', 'RESOLVED'],
            'INVESTIGATING' => ['RESOLVED'],
        ];

        if ($newStatus !== $currentStatus && ! in_array($newStatus, $allowedTransitions[$currentStatus] ?? [], true)) {
            $allowedList = implode(', ', $allowedTransitions[$currentStatus] ?? []);
            throw ValidationException::withMessages([
                'status' => "Transisi status exception dari '{$currentStatus}' ke '{$newStatus}' tidak diperbolehkan. Status yang diizinkan: {$allowedList}.",
            ]);
        }

        // Jika status menjadi RESOLVED, wajib mengisi resolution_note
        if ($newStatus === 'RESOLVED' && empty($data['resolution_note'])) {
            throw ValidationException::withMessages([
                'resolution_note' => 'Catatan penyelesaian (resolution note) wajib diisi saat menyelesaikan kendala (RESOLVED).',
            ]);
        }

        return DB::transaction(function () use ($exception, $data, $actor, $newStatus) {
            $oldValues = $exception->toArray();

            $exception->status = $newStatus;

            if (isset($data['resolution_note'])) {
                $exception->resolution_note = $data['resolution_note'];
            }

            if ($newStatus === 'RESOLVED') {
                $exception->resolved_by = $actor->id;
                $exception->resolved_at = now();
            }

            $exception->save();

            // Notifikasi in-app untuk customer
            $shipment = $exception->shipment;
            if ($shipment && $shipment->customer_id) {
                $isResolved = ($newStatus === 'RESOLVED');
                $title = $isResolved
                    ? "Kendala Terselesaikan: {$shipment->tracking_number}"
                    : "Pembaruan Penanganan Kendala: {$shipment->tracking_number}";

                $message = $isResolved
                    ? "Kendala operasional '{$exception->type}' pada pengiriman {$shipment->tracking_number} telah diselesaikan. Catatan resolusi: {$exception->resolution_note}"
                    : "Kendala operasional '{$exception->type}' pada pengiriman {$shipment->tracking_number} kini dalam tahap penanganan ({$newStatus}).";

                Notification::create([
                    'user_id' => $shipment->customer_id,
                    'shipment_id' => $shipment->id,
                    'title' => $title,
                    'message' => $message,
                    'is_read' => false,
                    'read_at' => null,
                ]);
            }

            // Catat ke audit_logs
            AuditLog::create([
                'user_id' => $actor->id,
                'action' => 'UPDATE_EXCEPTION',
                'target_type' => ShipmentException::class,
                'target_id' => $exception->id,
                'old_values' => $oldValues,
                'new_values' => $exception->fresh()->toArray(),
                'ip_address' => request()->ip(),
            ]);

            return $exception->fresh(['shipment', 'reporter', 'resolver']);
        });
    }
}
