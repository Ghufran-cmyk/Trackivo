<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\TrackingEvent;
use App\Models\User;
use App\Services\ShipmentService;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PhaseTwoMasterDataAndShipmentTest extends TestCase
{
    protected User $admin;
    protected User $operator;
    protected User $customer1;
    protected User $customer2;
    protected Carrier $carrier;
    protected Location $origin;
    protected Location $destination;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin', 'email' => 'testadmin@trackivo.com']);
        $this->operator = User::where('role', 'operator')->first() ?? User::factory()->create(['role' => 'operator', 'email' => 'testoperator@trackivo.com']);
        $this->customer1 = User::where('role', 'customer')->first() ?? User::factory()->create(['role' => 'customer', 'email' => 'testcust1@trackivo.com']);
        $this->customer2 = User::where('role', 'customer')->skip(1)->first() ?? User::factory()->create(['role' => 'customer', 'email' => 'testcust2@trackivo.com']);

        $this->carrier = Carrier::first() ?? Carrier::create([
            'code' => 'TESTC',
            'name' => 'Test Carrier',
            'is_active' => true,
        ]);

        $this->origin = Location::first() ?? Location::create([
            'code' => 'TSTORG',
            'name' => 'Test Origin',
            'country' => 'Indonesia',
            'type' => 'PORT',
            'latitude' => -6.1,
            'longitude' => 106.8,
        ]);

        $this->destination = Location::skip(1)->first() ?? Location::create([
            'code' => 'TSTDST',
            'name' => 'Test Destination',
            'country' => 'Singapore',
            'type' => 'PORT',
            'latitude' => 1.3,
            'longitude' => 103.8,
        ]);
    }

    public function test_state_machine_validates_transitions_strictly(): void
    {
        $service = app(ShipmentService::class);

        // Valid transitions
        $this->assertTrue($service->canTransition('CREATED', 'BOOKED'));
        $this->assertTrue($service->canTransition('CREATED', 'CANCELLED'));
        $this->assertTrue($service->canTransition('BOOKED', 'PICKED_UP'));
        $this->assertTrue($service->canTransition('BOOKED', 'CANCELLED'));
        $this->assertTrue($service->canTransition('PICKED_UP', 'IN_TRANSIT'));
        $this->assertTrue($service->canTransition('IN_TRANSIT', 'DELIVERED'));

        // Invalid transitions
        $this->assertFalse($service->canTransition('CREATED', 'DELIVERED'));
        $this->assertFalse($service->canTransition('CREATED', 'IN_TRANSIT'));
        $this->assertFalse($service->canTransition('DELIVERED', 'IN_TRANSIT'));
        $this->assertFalse($service->canTransition('DELIVERED', 'CANCELLED'));
        $this->assertFalse($service->canTransition('CANCELLED', 'CREATED'));
        $this->assertEmpty($service->getAllowedNextStatuses('DELIVERED'));
        $this->assertEmpty($service->getAllowedNextStatuses('CANCELLED'));
    }

    public function test_shipment_service_creates_shipment_with_items_initial_event_and_audit(): void
    {
        $service = app(ShipmentService::class);

        $shipmentData = [
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'OCEAN',
            'cargo_type' => 'Automotive Parts',
            'weight_kg' => 1500.00,
            'volume_cbm' => 20.00,
            'expected_departure' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'expected_arrival' => now()->addDays(5)->format('Y-m-d H:i:s'),
        ];

        $items = [
            [
                'item_name' => 'Engine Pistons',
                'quantity' => 100,
                'weight_kg' => 800.00,
                'description' => 'Box A',
            ],
            [
                'item_name' => 'Brake Discs',
                'quantity' => 50,
                'weight_kg' => 700.00,
                'description' => 'Box B',
            ],
        ];

        $shipment = $service->createShipment($shipmentData, $items, $this->admin);

        $this->assertNotNull($shipment);
        $this->assertStringStartsWith('TRK-' . date('Y') . '-', $shipment->tracking_number);
        $this->assertEquals('CREATED', $shipment->status);
        $this->assertCount(2, $shipment->items);

        // Check initial tracking event
        $initialEvent = $shipment->trackingEvents()->where('status', 'CREATED')->first();
        $this->assertNotNull($initialEvent);
        $this->assertEquals($this->origin->id, $initialEvent->location_id);

        // Check audit log
        $audit = AuditLog::where('target_id', $shipment->id)
            ->where('action', 'CREATE_SHIPMENT')
            ->first();
        $this->assertNotNull($audit);
    }

    public function test_shipment_service_transitions_status_and_records_event_and_audit(): void
    {
        $service = app(ShipmentService::class);

        $shipment = $service->createShipment([
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'AIR',
            'cargo_type' => 'Pharmaceuticals',
            'weight_kg' => 200.00,
            'expected_departure' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_arrival' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ], [
            ['item_name' => 'Vaccines', 'quantity' => 20, 'weight_kg' => 200.00],
        ], $this->operator);

        // Valid transition: CREATED -> BOOKED
        $updated = $service->transitionStatus($shipment, 'BOOKED', $this->operator, 'Booking confirmed by airline');
        $this->assertEquals('BOOKED', $updated->status);

        // Verify tracking event
        $bookedEvent = $updated->trackingEvents()->where('status', 'BOOKED')->first();
        $this->assertNotNull($bookedEvent);
        $this->assertStringContainsString('Booking confirmed by airline', $bookedEvent->description);

        // Invalid transition: BOOKED -> DELIVERED (Must throw ValidationException)
        $this->expectException(ValidationException::class);
        $service->transitionStatus($updated, 'DELIVERED', $this->operator);
    }

    public function test_terminal_shipment_cannot_be_updated(): void
    {
        $service = app(ShipmentService::class);

        $shipment = $service->createShipment([
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'LAND',
            'cargo_type' => 'Electronics',
            'weight_kg' => 500.00,
            'expected_departure' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_arrival' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ], [
            ['item_name' => 'Smartphones', 'quantity' => 10, 'weight_kg' => 500.00],
        ], $this->admin);

        // Transition to CANCELLED (Terminal)
        $service->transitionStatus($shipment, 'CANCELLED', $this->admin, 'Client requested cancellation');

        // Attempting to update cancelled shipment must throw exception
        $this->expectException(\DomainException::class);
        $service->updateShipment($shipment, ['cargo_type' => 'Updated Electronics'], [], $this->admin);
    }

    public function test_admin_can_access_carriers_locations_and_users(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('carriers.index'))->assertStatus(200)->assertSee('Manajemen Carriers');
        $this->get(route('locations.index'))->assertStatus(200)->assertSee('Manajemen Lokasi');
        $this->get(route('users.index'))->assertStatus(200)->assertSee('Manajemen Pengguna');
    }

    public function test_operator_and_customer_cannot_access_admin_master_data(): void
    {
        // Operator
        $this->actingAs($this->operator);
        $this->get(route('carriers.index'))->assertStatus(403);
        $this->get(route('locations.index'))->assertStatus(403);
        $this->get(route('users.index'))->assertStatus(403);

        // Customer
        $this->actingAs($this->customer1);
        $this->get(route('carriers.index'))->assertStatus(403);
        $this->get(route('locations.index'))->assertStatus(403);
        $this->get(route('users.index'))->assertStatus(403);
    }

    public function test_customer_can_only_see_their_own_shipments(): void
    {
        $service = app(ShipmentService::class);

        // Create shipment for Customer 1
        $shipment1 = $service->createShipment([
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'AIR',
            'cargo_type' => 'Cust 1 Goods',
            'weight_kg' => 100.00,
            'expected_departure' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_arrival' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ], [
            ['item_name' => 'Item 1', 'quantity' => 5, 'weight_kg' => 100.00],
        ], $this->admin);

        // Create shipment for Customer 2
        $shipment2 = $service->createShipment([
            'customer_id' => $this->customer2->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'OCEAN',
            'cargo_type' => 'Cust 2 Secret Cargo',
            'weight_kg' => 200.00,
            'expected_departure' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_arrival' => now()->addDays(4)->format('Y-m-d H:i:s'),
        ], [
            ['item_name' => 'Item 2', 'quantity' => 10, 'weight_kg' => 200.00],
        ], $this->admin);

        // Acting as Customer 1
        $this->actingAs($this->customer1);
        $indexResponse = $this->get(route('shipments.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee($shipment1->tracking_number);
        $indexResponse->assertDontSee($shipment2->tracking_number);

        // Customer 1 viewing own shipment
        $this->get(route('shipments.show', $shipment1))->assertStatus(200);

        // Customer 1 trying to view Customer 2 shipment -> 403 Forbidden
        $this->get(route('shipments.show', $shipment2))->assertStatus(403);
    }

    public function test_customer_cannot_create_or_mutate_shipments(): void
    {
        $this->actingAs($this->customer1);

        $this->get(route('shipments.create'))->assertStatus(403);
        $this->post(route('shipments.store'), [])->assertStatus(403);
    }

    public function test_admin_cannot_delete_active_in_transit_shipment(): void
    {
        $service = app(ShipmentService::class);

        $shipment = $service->createShipment([
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'OCEAN',
            'cargo_type' => 'Protected Operational Cargo',
            'weight_kg' => 300.00,
            'expected_departure' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_arrival' => now()->addDays(3)->format('Y-m-d H:i:s'),
        ], [
            ['item_name' => 'Protected Item', 'quantity' => 1, 'weight_kg' => 300.00],
        ], $this->admin);

        // Transition to BOOKED
        $service->transitionStatus($shipment, 'BOOKED', $this->admin);

        $this->actingAs($this->admin);
        $response = $this->delete(route('shipments.destroy', $shipment));
        $response->assertSessionHas('error');

        // Check shipment is still in DB
        $this->assertDatabaseHas('shipments', ['id' => $shipment->id]);
    }

    public function test_admin_can_delete_created_or_cancelled_shipment(): void
    {
        $service = app(ShipmentService::class);

        $shipment = $service->createShipment([
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'OCEAN',
            'cargo_type' => 'Deletable Draft Cargo',
            'weight_kg' => 50.00,
            'expected_departure' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_arrival' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ], [
            ['item_name' => 'Draft Item', 'quantity' => 1, 'weight_kg' => 50.00],
        ], $this->admin);

        $this->actingAs($this->admin);
        $response = $this->delete(route('shipments.destroy', $shipment));
        $response->assertRedirect(route('shipments.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('shipments', ['id' => $shipment->id]);
    }
}
