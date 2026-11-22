<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\TrackingEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ShipmentService
{
    /**
     * Definisi transisi status yang sah (State Machine).
     */
    public const VALID_TRANSITIONS = [
        'CREATED'    => ['BOOKED', 'CANCELLED'],
        'BOOKED'     => ['PICKED_UP', 'CANCELLED'],
        'PICKED_UP'  => ['IN_TRANSIT', 'CANCELLED'],
        'IN_TRANSIT' => ['DELIVERED', 'CANCELLED'],
        'DELIVERED'  => [],
        'CANCELLED'  => [],
    ];

    /**
     * Cek apakah transisi dari currentStatus ke newStatus diizinkan.
     */
    public function canTransition(string $currentStatus, string $newStatus): bool
    {
        $allowed = self::VALID_TRANSITIONS[$currentStatus] ?? [];

        return in_array($newStatus, $allowed, true);
    }

    /**
     * Dapatkan daftar status berikutnya yang sah dari status saat ini.
     */
    public function getAllowedNextStatuses(string $currentStatus): array
    {
        return self::VALID_TRANSITIONS[$currentStatus] ?? [];
    }

    /**
     * Buat shipment baru beserta items, tracking event awal, dan audit log.
     */
    public function createShipment(array $data, array $items, User $actor): Shipment
    {
        return DB::transaction(function () use ($data, $items, $actor) {
            // Auto-generate tracking number unik jika belum disediakan
            if (empty($data['tracking_number'])) {
                do {
                    $trackingNumber = 'TRK-' . date('Y') . '-' . strtoupper(Str::random(6));
                } while (Shipment::where('tracking_number', $trackingNumber)->exists());

                $data['tracking_number'] = $trackingNumber;
            }

            // Status awal selalu CREATED
            $data['status'] = 'CREATED';

            // Default estimated_arrival = expected_arrival jika tidak diset
            if (empty($data['estimated_arrival']) && ! empty($data['expected_arrival'])) {
                $data['estimated_arrival'] = $data['expected_arrival'];
            }

            $data['created_by'] = $actor->id;
            $data['delay_hours'] = $data['delay_hours'] ?? 0;
            $data['delay_severity'] = $data['delay_severity'] ?? 'NONE';

            // Pisahkan items dari data utama shipment jika ada
            unset($data['items']);

            $shipment = Shipment::create($data);

            // Simpan items pengiriman
            foreach ($items as $item) {
                if (empty($item['item_name'])) {
                    continue;
                }
                $shipment->items()->create([
                    'item_name' => $item['item_name'],
                    'quantity' => (int) ($item['quantity'] ?? 1),
                    'weight_kg' => (float) ($item['weight_kg'] ?? 0),
                    'description' => $item['description'] ?? null,
                ]);
            }

            // Eager load originLocation untuk koordinat tracking event
            $shipment->load('originLocation');

            // Buat 1 tracking_event awal
            TrackingEvent::create([
                'shipment_id' => $shipment->id,
                'location_id' => $shipment->origin_location_id,
                'status' => 'CREATED',
                'is_exception' => false,
                'latitude' => $shipment->originLocation?->latitude,
                'longitude' => $shipment->originLocation?->longitude,
                'event_time' => now(),
                'description' => 'Pengiriman berhasil dibuat di sistem Trackivo.',
                'created_by' => $actor->id,
            ]);

            // Catat ke audit_logs
            AuditLog::create([
                'user_id' => $actor->id,
                'action' => 'CREATE_SHIPMENT',
                'target_type' => Shipment::class,
                'target_id' => $shipment->id,
                'old_values' => null,
                'new_values' => $shipment->toArray(),
                'ip_address' => request()->ip(),
            ]);

            return $shipment->load([
                'items',
                'trackingEvents.location',
                'customer',
                'carrier',
                'originLocation',
                'destinationLocation',
            ]);
        });
    }

    /**
     * Perbarui data pengiriman (hanya detail non-status).
     */
    public function updateShipment(Shipment $shipment, array $data, array $items, User $actor): Shipment
    {
        return DB::transaction(function () use ($shipment, $data, $items, $actor) {
            // Cegah modifikasi jika shipment sudah berstatus final/terminal
            if (in_array($shipment->status, ['DELIVERED', 'CANCELLED'], true)) {
                throw new \DomainException("Pengiriman dengan status {$shipment->status} sudah bersifat final dan tidak dapat diubah.");
            }

            $oldValues = $shipment->getAttributes();

            // Jangan izinkan pembaruan status atau tracking_number melalui method ini
            unset($data['status'], $data['tracking_number'], $data['items']);

            // Sinkronkan estimated_arrival jika expected_arrival berubah
            if (isset($data['expected_arrival']) && empty($data['estimated_arrival'])) {
                $data['estimated_arrival'] = $data['expected_arrival'];
            }

            $shipment->update($data);

            // Sinkronkan items pengiriman
            if (! empty($items)) {
                $shipment->items()->delete();
                foreach ($items as $item) {
                    if (empty($item['item_name'])) {
                        continue;
                    }
                    $shipment->items()->create([
                        'item_name' => $item['item_name'],
                        'quantity' => (int) ($item['quantity'] ?? 1),
                        'weight_kg' => (float) ($item['weight_kg'] ?? 0),
                        'description' => $item['description'] ?? null,
                    ]);
                }
            }

            // Catat ke audit_logs
            AuditLog::create([
                'user_id' => $actor->id,
                'action' => 'UPDATE_SHIPMENT',
                'target_type' => Shipment::class,
                'target_id' => $shipment->id,
                'old_values' => $oldValues,
                'new_values' => $shipment->fresh()->toArray(),
                'ip_address' => request()->ip(),
            ]);

            return $shipment->fresh([
                'items',
                'trackingEvents.location',
                'customer',
                'carrier',
                'originLocation',
                'destinationLocation',
            ]);
        });
    }

    /**
     * Transisi status pengiriman dengan validasi State Machine ketat.
     */
    public function transitionStatus(Shipment $shipment, string $newStatus, User $actor, ?string $note = null): Shipment
    {
        $currentStatus = $shipment->status;

        // Validasi aturan State Machine
        if (! $this->canTransition($currentStatus, $newStatus)) {
            $allowed = $this->getAllowedNextStatuses($currentStatus);
            $allowedText = ! empty($allowed) ? implode(', ', $allowed) : 'Tidak ada (status final)';

            throw ValidationException::withMessages([
                'status' => "Transisi status dari '{$currentStatus}' ke '{$newStatus}' tidak diperbolehkan. Status selanjutnya yang sah: {$allowedText}.",
            ]);
        }

        return DB::transaction(function () use ($shipment, $currentStatus, $newStatus, $actor, $note) {
            // Perbarui status shipment
            $shipment->status = $newStatus;
            $shipment->save();

            // Deskripsi event berdasarkan status
            $descriptions = [
                'BOOKED'     => 'Kargo telah dibooking oleh carrier.',
                'PICKED_UP'  => 'Kargo telah dijemput dan diterima oleh carrier.',
                'IN_TRANSIT' => 'Kargo sedang dalam perjalanan menuju lokasi tujuan.',
                'DELIVERED'  => 'Kargo telah berhasil sampai dan diterima di tujuan akhir.',
                'CANCELLED'  => 'Pengiriman kargo telah dibatalkan.',
            ];

            $desc = $descriptions[$newStatus] ?? "Status pengiriman diubah menjadi {$newStatus}.";
            if (! empty($note)) {
                $desc .= ' Catatan: ' . $note;
            }

            // Tentukan lokasi & koordinat untuk tracking event baru
            $locationId = null;
            $latitude = null;
            $longitude = null;

            if ($newStatus === 'DELIVERED') {
                $location = $shipment->destinationLocation;
                $locationId = $shipment->destination_location_id;
                $latitude = $location?->latitude;
                $longitude = $location?->longitude;
            } elseif (in_array($newStatus, ['BOOKED', 'PICKED_UP'], true)) {
                $location = $shipment->originLocation;
                $locationId = $shipment->origin_location_id;
                $latitude = $location?->latitude;
                $longitude = $location?->longitude;
            }

            // Tambahkan record baru ke tracking_events (tidak menimpa record lama)
            TrackingEvent::create([
                'shipment_id' => $shipment->id,
                'location_id' => $locationId,
                'status' => $newStatus,
                'is_exception' => false,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'event_time' => now(),
                'description' => $desc,
                'created_by' => $actor->id,
            ]);

            // Catat perubahan ke audit_logs
            AuditLog::create([
                'user_id' => $actor->id,
                'action' => 'STATUS_TRANSITION',
                'target_type' => Shipment::class,
                'target_id' => $shipment->id,
                'old_values' => ['status' => $currentStatus],
                'new_values' => ['status' => $newStatus, 'note' => $note],
                'ip_address' => request()->ip(),
            ]);

            return $shipment->fresh([
                'items',
                'trackingEvents.location',
                'customer',
                'carrier',
                'originLocation',
                'destinationLocation',
            ]);
        });
    }

    /**
     * Hapus shipment beserta items dan audit log (hanya status CREATED atau CANCELLED).
     */
    public function deleteShipment(Shipment $shipment, User $actor): void
    {
        if (! in_array($shipment->status, ['CREATED', 'CANCELLED'], true)) {
            throw ValidationException::withMessages([
                'status' => "Pengiriman berstatus '{$shipment->status}' tidak dapat dihapus demi keamanan audit operasional. Hanya status CREATED atau CANCELLED yang dapat dihapus.",
            ]);
        }

        DB::transaction(function () use ($shipment, $actor) {
            $oldValues = $shipment->toArray();
            $shipmentId = $shipment->id;

            $shipment->items()->delete();
            $shipment->delete();

            AuditLog::create([
                'user_id' => $actor->id,
                'action' => 'DELETE_SHIPMENT',
                'target_type' => Shipment::class,
                'target_id' => $shipmentId,
                'old_values' => $oldValues,
                'new_values' => null,
                'ip_address' => request()->ip(),
            ]);
        });
    }
}
