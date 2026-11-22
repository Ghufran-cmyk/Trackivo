<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShipmentService;
use App\Services\TrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ShipmentLifecycleAndTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $operator;
    protected User $customer;
    protected Carrier $carrier;
    protected Location $origin;
    protected Location $destination;
    protected ShipmentService $shipmentService;
    protected TrackingService $trackingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->operator = User::where('role', 'operator')->first();
        $this->customer = User::where('role', 'customer')->first();
        $this->carrier = Carrier::first();
        $this->origin = Location::orderBy('id')->first();
        $this->destination = Location::orderByDesc('id')->first();

        $this->shipmentService = app(ShipmentService::class);
        $this->trackingService = app(TrackingService::class);
    }

    /**
     * Helper untuk membuat shipment baru berstatus CREATED.
     */
    protected function makeShipment(): Shipment
    {
        return Shipment::create([
            'tracking_number' => 'TRK-LIFECYCLE-' . uniqid(),
            'customer_id' => $this->customer->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'status' => 'CREATED',
            'transport_mode' => 'OCEAN',
            'cargo_type' => 'Industrial Machinery',
            'weight_kg' => 2500.0,
            'expected_departure' => now()->addDays(1),
            'expected_arrival' => now()->addDays(8),
            'estimated_arrival' => now()->addDays(8),
            'delay_hours' => 0,
            'delay_severity' => 'NONE',
            'created_by' => $this->operator->id,
        ]);
    }

    /**
     * State Machine mengizinkan transisi valid:
     * CREATED -> BOOKED -> PICKED_UP -> IN_TRANSIT -> DELIVERED.
     */
    public function test_state_machine_allows_sequential_valid_transitions(): void
    {
        $shipment = $this->makeShipment();
        $this->assertEquals('CREATED', $shipment->status);

        // 1. CREATED -> BOOKED
        $shipment = $this->shipmentService->transitionStatus($shipment, 'BOOKED', $this->operator);
        $this->assertEquals('BOOKED', $shipment->status);

        // 2. BOOKED -> PICKED_UP
        $shipment = $this->shipmentService->transitionStatus($shipment, 'PICKED_UP', $this->operator);
        $this->assertEquals('PICKED_UP', $shipment->status);

        // 3. PICKED_UP -> IN_TRANSIT
        $shipment = $this->shipmentService->transitionStatus($shipment, 'IN_TRANSIT', $this->operator);
        $this->assertEquals('IN_TRANSIT', $shipment->status);

        // 4. IN_TRANSIT -> DELIVERED
        $shipment = $this->shipmentService->transitionStatus($shipment, 'DELIVERED', $this->operator);
        $this->assertEquals('DELIVERED', $shipment->status);
    }

    /**
     * State Machine menolak lompatan status tidak valid (misal CREATED langsung ke DELIVERED)
     * dan menolak perubahan status apa pun setelah mencapai status final DELIVERED.
     */
    public function test_state_machine_rejects_invalid_skips_and_terminal_mutations(): void
    {
        $shipment = $this->makeShipment();
        $this->assertEquals('CREATED', $shipment->status);

        // 1. Lompatan tidak valid: CREATED langsung ke DELIVERED ditolak ValidationException
        $this->expectException(ValidationException::class);
        $this->shipmentService->transitionStatus($shipment, 'DELIVERED', $this->operator);
    }

    /**
     * State Machine menolak perubahan status setelah DELIVERED.
     */
    public function test_state_machine_rejects_transitions_after_delivered(): void
    {
        $shipment = $this->makeShipment();

        // Alur transisi sampai DELIVERED
        $shipment = $this->shipmentService->transitionStatus($shipment, 'BOOKED', $this->operator);
        $shipment = $this->shipmentService->transitionStatus($shipment, 'PICKED_UP', $this->operator);
        $shipment = $this->shipmentService->transitionStatus($shipment, 'IN_TRANSIT', $this->operator);
        $shipment = $this->shipmentService->transitionStatus($shipment, 'DELIVERED', $this->operator);
        $this->assertEquals('DELIVERED', $shipment->status);

        // Mencoba ubah kembali ke IN_TRANSIT atau CANCELLED harus melempar ValidationException
        $this->expectException(ValidationException::class);
        $this->shipmentService->transitionStatus($shipment, 'IN_TRANSIT', $this->operator);
    }

    /**
     * TrackingService menambahkan event baru tanpa menimpa event lama,
     * serta menolak event_time yang lebih awal dari event terakhir.
     */
    public function test_tracking_service_appends_events_and_enforces_chronological_order(): void
    {
        $shipment = $this->makeShipment();

        $t1 = now()->subHours(5);
        $event1 = $this->trackingService->addTrackingEvent($shipment, [
            'status' => 'BOOKED',
            'location_id' => $this->origin->id,
            'event_time' => $t1->toIso8601String(),
            'description' => 'Event pertama: Booking diterima.',
        ], $this->operator);

        $this->assertDatabaseHas('tracking_events', [
            'id' => $event1->id,
            'description' => 'Event pertama: Booking diterima.',
        ]);

        $t2 = now()->subHours(2);
        $event2 = $this->trackingService->addTrackingEvent($shipment, [
            'status' => 'PICKED_UP',
            'location_id' => $this->origin->id,
            'event_time' => $t2->toIso8601String(),
            'description' => 'Event kedua: Barang dijemput kurir.',
        ], $this->operator);

        // Pastikan event pertama TIDAK terhapus/tertimpa
        $this->assertDatabaseHas('tracking_events', ['id' => $event1->id]);
        $this->assertDatabaseHas('tracking_events', ['id' => $event2->id]);
        $this->assertCount(2, $shipment->fresh()->trackingEvents);

        // Mencoba menambahkan event dengan waktu lebih lampau dari event terakhir (t2 - 1 jam)
        $tInvalid = $t2->copy()->subHour();
        $this->expectException(ValidationException::class);
        $this->trackingService->addTrackingEvent($shipment, [
            'status' => 'PICKED_UP',
            'location_id' => $this->origin->id,
            'event_time' => $tInvalid->toIso8601String(),
            'description' => 'Event mundur tidak valid.',
        ], $this->operator);
    }
}
