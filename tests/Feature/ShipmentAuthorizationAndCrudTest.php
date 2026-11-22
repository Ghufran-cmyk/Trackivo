<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShipmentAuthorizationAndCrudTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

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

        $this->admin = User::where('role', 'admin')->first();
        $this->operator = User::where('role', 'operator')->first();
        $this->customer1 = User::where('role', 'customer')->orderBy('id')->first();
        $this->customer2 = User::where('role', 'customer')->orderByDesc('id')->first();

        $this->carrier = Carrier::first();
        $this->origin = Location::orderBy('id')->first();
        $this->destination = Location::orderByDesc('id')->first();
    }

    /**
     * Admin dan Operator dapat membuat shipment via API, sedangkan Customer ditolak (403).
     */
    public function test_admin_and_operator_can_create_shipment_api_customer_forbidden(): void
    {
        $payload = [
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'AIR',
            'cargo_type' => 'Medical Equipment',
            'weight_kg' => 450.0,
            'expected_departure' => now()->addDay()->toIso8601String(),
            'expected_arrival' => now()->addDays(3)->toIso8601String(),
            'items' => [
                [
                    'item_name' => 'Oxygen Concentrator',
                    'quantity' => 10,
                    'weight_kg' => 450.0,
                    'description' => 'Fragile cargo',
                ],
            ],
        ];

        // 1. Admin berhasil membuat shipment (201)
        Sanctum::actingAs($this->admin);
        $adminRes = $this->postJson('/api/shipments', $payload);
        $adminRes->assertStatus(201)
            ->assertJsonPath('data.cargo_type', 'Medical Equipment');

        // 2. Operator berhasil membuat shipment (201)
        Sanctum::actingAs($this->operator);
        $payload['cargo_type'] = 'Medical Vaccines';
        $opRes = $this->postJson('/api/shipments', $payload);
        $opRes->assertStatus(201)
            ->assertJsonPath('data.cargo_type', 'Medical Vaccines');

        // 3. Customer ditolak 403 via API
        Sanctum::actingAs($this->customer1);
        $custRes = $this->postJson('/api/shipments', $payload);
        $custRes->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengakses resource ini.',
            ]);

        // 4. Customer ditolak 403 via Web
        $custWebRes = $this->actingAs($this->customer1)->post('/shipments', $payload);
        $custWebRes->assertStatus(403);
    }

    /**
     * Customer hanya melihat shipment miliknya di daftar (API & Web) dan ditolak (403) saat mengakses detail milik customer lain.
     */
    public function test_customer_isolation_on_shipments_list_and_detail(): void
    {
        // Buat 1 shipment untuk customer1 dan 1 untuk customer2
        $shipmentCust1 = Shipment::create([
            'tracking_number' => 'TRK-TEST-CUST1',
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'status' => 'CREATED',
            'transport_mode' => 'OCEAN',
            'cargo_type' => 'Cust 1 Furniture',
            'weight_kg' => 1000.0,
            'expected_departure' => now()->addDays(2),
            'expected_arrival' => now()->addDays(7),
            'created_by' => $this->admin->id,
        ]);

        $shipmentCust2 = Shipment::create([
            'tracking_number' => 'TRK-TEST-CUST2',
            'customer_id' => $this->customer2->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'status' => 'CREATED',
            'transport_mode' => 'LAND',
            'cargo_type' => 'Cust 2 Electronics',
            'weight_kg' => 500.0,
            'expected_departure' => now()->addDays(1),
            'expected_arrival' => now()->addDays(3),
            'created_by' => $this->admin->id,
        ]);

        // Customer 1 mengecek daftar API: hanya melihat miliknya
        Sanctum::actingAs($this->customer1);
        $resList = $this->getJson('/api/shipments');

        $resList->assertStatus(200);
        $resList->assertSee('TRK-TEST-CUST1');
        $resList->assertDontSee('TRK-TEST-CUST2');

        // Customer 1 mengakses detail miliknya -> 200
        $resDetailSelf = $this->getJson('/api/shipments/' . $shipmentCust1->id);
        $resDetailSelf->assertStatus(200)
            ->assertJsonPath('data.tracking_number', 'TRK-TEST-CUST1');

        // Customer 1 mengakses detail milik Customer 2 -> 403
        $resDetailOther = $this->getJson('/api/shipments/' . $shipmentCust2->id);
        $resDetailOther->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengakses resource ini.',
            ]);

        // Web detail check: Customer 1 mengakses detail milik Customer 2 -> 403
        $webResOther = $this->actingAs($this->customer1)->get('/shipments/' . $shipmentCust2->id);
        $webResOther->assertStatus(403);
    }

    /**
     * Operator ditolak (403) saat mengakses master data admin (/users, /carriers, /settings, /audit-logs),
     * serta DELETE /api/shipments/{id} dan POST /api/carriers.
     */
    public function test_operator_forbidden_from_admin_exclusive_resources(): void
    {
        // Web admin routes 403 for operator
        $this->actingAs($this->operator)->get('/users')->assertStatus(403);
        $this->actingAs($this->operator)->get('/carriers')->assertStatus(403);
        $this->actingAs($this->operator)->get('/settings')->assertStatus(403);
        $this->actingAs($this->operator)->get('/audit-logs')->assertStatus(403);

        // API DELETE /api/shipments/{id} ditolak 403 untuk Operator
        $shipment = Shipment::first();
        Sanctum::actingAs($this->operator);

        $delRes = $this->deleteJson('/api/shipments/' . $shipment->id);
        $delRes->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengakses resource ini.',
            ]);

        // API POST /api/carriers ditolak 403 untuk Operator
        $carrierRes = $this->postJson('/api/carriers', [
            'code' => 'NEWCAR',
            'name' => 'New Carrier Logistics',
        ]);
        $carrierRes->assertStatus(403);

        // Admin berhasil mengakses DELETE shipment berstatus CREATED
        $newShipment = Shipment::create([
            'tracking_number' => 'TRK-DEL-ADMIN',
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'status' => 'CREATED',
            'transport_mode' => 'AIR',
            'cargo_type' => 'Sample',
            'weight_kg' => 10.0,
            'expected_departure' => now()->addDay(),
            'expected_arrival' => now()->addDays(2),
            'created_by' => $this->admin->id,
        ]);

        Sanctum::actingAs($this->admin);
        $adminDelRes = $this->deleteJson('/api/shipments/' . $newShipment->id);
        $adminDelRes->assertStatus(200);
    }

    /**
     * Validasi shipment menolak jika origin_location_id == destination_location_id atau expected_arrival <= expected_departure (422).
     */
    public function test_shipment_validation_rejects_identical_locations_and_invalid_dates(): void
    {
        Sanctum::actingAs($this->admin);

        // 1. Origin sama dengan Destination
        $resSameLoc = $this->postJson('/api/shipments', [
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->origin->id, // Lokasi sama!
            'transport_mode' => 'AIR',
            'cargo_type' => 'Goods',
            'weight_kg' => 100.0,
            'expected_departure' => now()->addDay()->toIso8601String(),
            'expected_arrival' => now()->addDays(3)->toIso8601String(),
            'items' => [
                ['item_name' => 'Item 1', 'quantity' => 1, 'weight_kg' => 100.0],
            ],
        ]);

        $resSameLoc->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validasi gagal.',
            ])
            ->assertJsonValidationErrors(['destination_location_id']);

        // 2. Expected arrival lebih awal atau sama dengan expected departure
        $resInvalidDate = $this->postJson('/api/shipments', [
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'AIR',
            'cargo_type' => 'Goods',
            'weight_kg' => 100.0,
            'expected_departure' => now()->addDays(5)->toIso8601String(),
            'expected_arrival' => now()->addDays(2)->toIso8601String(), // Tiba sebelum berangkat!
            'items' => [
                ['item_name' => 'Item 1', 'quantity' => 1, 'weight_kg' => 100.0],
            ],
        ]);

        $resInvalidDate->assertStatus(422)
            ->assertJsonValidationErrors(['expected_arrival']);
    }
}
