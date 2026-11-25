<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShippingRateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentPaymentAndReceiptTest extends TestCase
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
     * 1. Pembuatan shipment baru otomatis menghitung rincian biaya Rupiah & menghasilkan invoice_number unik
     *    baik dengan input pembayaran eksplisit maupun tanpa input pembayaran (kompatibilitas mundur).
     */
    public function test_shipment_creation_calculates_rates_and_generates_invoice(): void
    {
        $this->actingAs($this->admin);

        // Kasus A: Tanpa input biaya (Kompatibilitas mundur 100%)
        $payloadDefault = [
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'AIR',
            'cargo_type' => 'Komponen Mesin',
            'weight_kg' => 100.0,
            'volume_cbm' => 2.0,
            'expected_departure' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_arrival' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'item_name' => 'Sparepart Turbo',
                    'quantity' => 2,
                    'weight_kg' => 100.0,
                    'description' => 'Box 1',
                ],
            ],
        ];

        $responseA = $this->post(route('shipments.store'), $payloadDefault);
        $responseA->assertRedirect();

        $shipmentA = Shipment::where('cargo_type', 'Komponen Mesin')->first();
        $this->assertNotNull($shipmentA);
        $this->assertNotNull($shipmentA->invoice_number);
        $this->assertStringStartsWith('INV-' . date('Y') . '-', $shipmentA->invoice_number);

        // Tarif AIR: Base Rp 1.500.000 + (100 * 8.500 = 850.000) + (2 * 600.000 = 1.200.000) = 3.550.000
        $this->assertEquals(1500000, $shipmentA->base_cost);
        $this->assertEquals(2050000, $shipmentA->weight_volume_cost);
        $this->assertEquals(0, $shipmentA->insurance_cost);
        $this->assertEquals(3550000, $shipmentA->total_amount);
        $this->assertEquals('BANK_TRANSFER', $shipmentA->payment_method);
        $this->assertEquals('UNPAID', $shipmentA->payment_status);
        $this->assertNull($shipmentA->paid_at);
        $this->assertNull($shipmentA->payment_confirmed_by);

        // Kasus B: Dengan input pembayaran eksplisit dan asuransi
        $payloadCustom = [
            'customer_id' => $this->customer1->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'transport_mode' => 'OCEAN',
            'cargo_type' => 'Mebel Rotan',
            'weight_kg' => 2000.0,
            'volume_cbm' => 10.0,
            'insurance_cost' => 500000,
            'payment_method' => 'QRIS',
            'payment_status' => 'PAID',
            'expected_departure' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_arrival' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'item_name' => 'Kursi Rotan',
                    'quantity' => 20,
                    'weight_kg' => 2000.0,
                    'description' => 'Rotan Alam',
                ],
            ],
        ];

        $responseB = $this->post(route('shipments.store'), $payloadCustom);
        $responseB->assertRedirect();

        $shipmentB = Shipment::where('cargo_type', 'Mebel Rotan')->first();
        $this->assertNotNull($shipmentB);
        $this->assertNotNull($shipmentB->invoice_number);
        $this->assertNotEquals($shipmentA->invoice_number, $shipmentB->invoice_number);

        // OCEAN: Base Rp 750.000 + (2000 * 1500 = 3.000.000) + (10 * 250.000 = 2.500.000) = 6.250.000 + insurance 500.000 = 6.750.000
        $this->assertEquals(750000, $shipmentB->base_cost);
        $this->assertEquals(5500000, $shipmentB->weight_volume_cost);
        $this->assertEquals(500000, $shipmentB->insurance_cost);
        $this->assertEquals(6750000, $shipmentB->total_amount);
        $this->assertEquals('QRIS', $shipmentB->payment_method);
        $this->assertEquals('PAID', $shipmentB->payment_status);
        $this->assertNotNull($shipmentB->paid_at);
        $this->assertEquals($this->admin->id, $shipmentB->payment_confirmed_by);
    }

    /**
     * 2. Admin & Operator dapat mengonfirmasi status pembayaran dari UNPAID menjadi PAID
     *    beserta pencatatan paid_at, payment_confirmed_by, dan AuditLog.
     */
    public function test_admin_and_operator_can_confirm_payment(): void
    {
        $unpaidShipment = Shipment::where('payment_status', 'UNPAID')->first();
        $this->assertNotNull($unpaidShipment);

        // Operator mengonfirmasi pembayaran
        $this->actingAs($this->operator);

        $response = $this->post(route('shipments.payment.confirm', $unpaidShipment), [
            'payment_method' => 'CASH',
        ]);

        $response->assertRedirect(route('shipments.show', $unpaidShipment));
        $response->assertSessionHas('success');

        $unpaidShipment->refresh();
        $this->assertEquals('PAID', $unpaidShipment->payment_status);
        $this->assertEquals('CASH', $unpaidShipment->payment_method);
        $this->assertNotNull($unpaidShipment->paid_at);
        $this->assertEquals($this->operator->id, $unpaidShipment->payment_confirmed_by);

        // Periksa jejak di AuditLog
        $log = AuditLog::where('target_id', $unpaidShipment->id)
            ->where('action', 'CONFIRM_PAYMENT')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals($this->operator->id, $log->user_id);
        $this->assertEquals('PAID', $log->new_values['payment_status']);
    }

    /**
     * 3. Customer ditolak (403) saat mencoba mengakses endpoint konfirmasi pembayaran.
     */
    public function test_customer_forbidden_from_confirming_payment(): void
    {
        $shipment = Shipment::first();
        $this->actingAs($this->customer1);

        $response = $this->post(route('shipments.payment.confirm', $shipment), [
            'payment_method' => 'BANK_TRANSFER',
        ]);

        $response->assertStatus(403);
    }

    /**
     * 4. Admin, Operator, dan Customer pemilik paket dapat membuka halaman struk (/shipments/{id}/receipt)
     *    dengan status 200 serta melihat invoice, resi, dan nominal Rupiah;
     *    sementara Customer lain yang bukan pemilik ditolak dengan 403.
     */
    public function test_receipt_access_and_authorization(): void
    {
        // Temukan shipment milik customer1
        $shipment = Shipment::where('customer_id', $this->customer1->id)->first();
        $this->assertNotNull($shipment);

        // A. Admin dapat mengakses struk (200)
        $this->actingAs($this->admin);
        $resAdmin = $this->get(route('shipments.receipt', $shipment));
        $resAdmin->assertStatus(200)
            ->assertSee($shipment->tracking_number)
            ->assertSee($shipment->invoice_number)
            ->assertSee(ShippingRateService::formatRupiah($shipment->total_amount));

        // B. Operator dapat mengakses struk (200)
        $this->actingAs($this->operator);
        $resOperator = $this->get(route('shipments.receipt', $shipment));
        $resOperator->assertStatus(200)
            ->assertSee($shipment->tracking_number);

        // C. Customer pemilik dapat mengakses struk (200)
        $this->actingAs($this->customer1);
        $resOwner = $this->get(route('shipments.receipt', $shipment));
        $resOwner->assertStatus(200)
            ->assertSee($shipment->tracking_number)
            ->assertSee($shipment->invoice_number);

        // D. Customer lain (customer2) yang bukan pemilik DITOLAK (403)
        $this->actingAs($this->customer2);
        $resNonOwner = $this->get(route('shipments.receipt', $shipment));
        $resNonOwner->assertStatus(403);
    }
}
