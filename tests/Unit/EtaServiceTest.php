<?php

namespace Tests\Unit;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Setting;
use App\Models\Shipment;
use App\Models\User;
use App\Services\EtaService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EtaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected EtaService $etaService;
    protected User $user;
    protected Carrier $carrier;
    protected Location $origin;
    protected Location $destination;

    protected function setUp(): void
    {
        parent::setUp();

        $this->etaService = app(EtaService::class);
        $this->user = User::first();
        $this->carrier = Carrier::first();
        $this->origin = Location::orderBy('id')->first();
        $this->destination = Location::orderByDesc('id')->first();
    }

    protected function makeShipmentWithEta(Carbon $expected, Carbon $estimated, string $status = 'IN_TRANSIT'): Shipment
    {
        return Shipment::create([
            'tracking_number' => 'TRK-ETA-' . uniqid(),
            'customer_id' => $this->user->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'status' => $status,
            'transport_mode' => 'AIR',
            'cargo_type' => 'Test Cargo',
            'weight_kg' => 100.0,
            'expected_departure' => $expected->copy()->subDays(2),
            'expected_arrival' => $expected,
            'estimated_arrival' => $estimated,
            'delay_hours' => 0,
            'delay_severity' => 'NONE',
            'created_by' => $this->user->id,
        ]);
    }

    /**
     * Menguji perhitungan On-Time (0 jam / estimated <= expected -> NONE).
     */
    public function test_evaluate_delay_on_time(): void
    {
        $base = Carbon::now();
        $shipment = $this->makeShipmentWithEta($base, $base->copy()->subHours(2)); // Lebih awal 2 jam

        $result = $this->etaService->evaluateDelay($shipment);

        $this->assertFalse($result['is_delayed']);
        $this->assertEquals(0, $result['delay_hours']);
        $this->assertEquals('NONE', $result['delay_severity']);
    }

    /**
     * Menguji perhitungan delay LOW (1-6 jam sesuai default threshold low_max = 6).
     */
    public function test_evaluate_delay_low_severity(): void
    {
        $base = Carbon::now();
        $shipment = $this->makeShipmentWithEta($base, $base->copy()->addHours(4)); // Terlambat 4 jam

        $result = $this->etaService->evaluateDelay($shipment);

        $this->assertTrue($result['is_delayed']);
        $this->assertEquals(4, $result['delay_hours']);
        $this->assertEquals('LOW', $result['delay_severity']);
    }

    /**
     * Menguji perhitungan delay MEDIUM (7-24 jam sesuai default threshold 6 < delay <= 24).
     */
    public function test_evaluate_delay_medium_severity(): void
    {
        $base = Carbon::now();
        $shipment = $this->makeShipmentWithEta($base, $base->copy()->addHours(12)); // Terlambat 12 jam

        $result = $this->etaService->evaluateDelay($shipment);

        $this->assertTrue($result['is_delayed']);
        $this->assertEquals(12, $result['delay_hours']);
        $this->assertEquals('MEDIUM', $result['delay_severity']);
    }

    /**
     * Menguji perhitungan delay HIGH (> 24 jam sesuai default threshold).
     */
    public function test_evaluate_delay_high_severity(): void
    {
        $base = Carbon::now();
        $shipment = $this->makeShipmentWithEta($base, $base->copy()->addHours(30)); // Terlambat 30 jam

        $result = $this->etaService->evaluateDelay($shipment);

        $this->assertTrue($result['is_delayed']);
        $this->assertEquals(30, $result['delay_hours']);
        $this->assertEquals('HIGH', $result['delay_severity']);
    }

    /**
     * Menguji perubahan threshold global di tabel settings dan recalculateActiveShipments()
     * yang mengubah severity pengiriman aktif (misal dari MEDIUM menjadi LOW jika batas low dinaikkan ke 12).
     */
    public function test_recalculate_active_shipments_adapts_to_setting_changes(): void
    {
        $base = Carbon::now();
        // Terlambat 10 jam: dengan default threshold (low_max=6), delay ini berstatus MEDIUM
        $shipment = $this->makeShipmentWithEta($base, $base->copy()->addHours(10));
        $this->etaService->syncShipmentDelay($shipment);

        $this->assertEquals('MEDIUM', $shipment->fresh()->delay_severity);
        $this->assertEquals(10, $shipment->fresh()->delay_hours);

        // Ubah threshold global: perpanjang low_max_hours menjadi 12 jam
        Setting::set('delay_low_max_hours', '12');

        // Jalankan recalculateActiveShipments
        $count = $this->etaService->recalculateActiveShipments();
        $this->assertGreaterThan(0, $count);

        // Severity pengiriman kini seharusnya turun menjadi LOW (karena 10 <= 12)
        $shipmentFresh = $shipment->fresh();
        $this->assertEquals('LOW', $shipmentFresh->delay_severity);
        $this->assertEquals(10, $shipmentFresh->delay_hours);
    }
}
