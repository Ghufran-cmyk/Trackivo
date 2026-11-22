<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Models\User;
use App\Services\ExceptionService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExceptionAndApiTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $admin;
    protected User $operator;
    protected User $customer;
    protected Carrier $carrier;
    protected Location $origin;
    protected Location $destination;
    protected ExceptionService $exceptionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('role', 'admin')->first();
        $this->operator = User::where('role', 'operator')->first();
        $this->customer = User::where('role', 'customer')->first();
        $this->carrier = Carrier::first();
        $this->origin = Location::orderBy('id')->first();
        $this->destination = Location::orderByDesc('id')->first();
        $this->exceptionService = app(ExceptionService::class);
    }

    /**
     * Melaporkan exception dengan delay_impact_hours otomatis menggeser estimated_arrival,
     * memperbarui delay_severity, dan membuat tracking_event dengan is_exception = true.
     */
    public function test_reporting_exception_shifts_estimated_arrival_and_creates_tracking_event(): void
    {
        $expected = Carbon::now()->addDays(5);
        $shipment = Shipment::create([
            'tracking_number' => 'TRK-EXC-' . uniqid(),
            'customer_id' => $this->customer->id,
            'carrier_id' => $this->carrier->id,
            'origin_location_id' => $this->origin->id,
            'destination_location_id' => $this->destination->id,
            'status' => 'IN_TRANSIT',
            'transport_mode' => 'OCEAN',
            'cargo_type' => 'Steel Coils',
            'weight_kg' => 15000.0,
            'expected_departure' => now()->subDay(),
            'expected_arrival' => $expected,
            'estimated_arrival' => $expected->copy(),
            'delay_hours' => 0,
            'delay_severity' => 'NONE',
            'created_by' => $this->operator->id,
        ]);

        $initialEstimated = $shipment->estimated_arrival->copy();

        // Laporkan exception cuaca buruk dengan dampak keterlambatan 14 jam via API
        Sanctum::actingAs($this->operator);
        $res = $this->postJson("/api/shipments/{$shipment->id}/exceptions", [
            'type' => 'WEATHER_DELAY',
            'severity' => 'MEDIUM',
            'delay_impact_hours' => 14,
            'description' => 'Typhoon warning in South China Sea.',
        ]);

        $res->assertStatus(201)
            ->assertJsonPath('data.type', 'WEATHER_DELAY')
            ->assertJsonPath('data.status', 'OPEN');

        $shipment->refresh();

        // 1. Estimated arrival bergeser +14 jam
        $this->assertEquals(
            $initialEstimated->addHours(14)->toIso8601String(),
            $shipment->estimated_arrival->toIso8601String()
        );

        // 2. Delay severity terupdate menjadi MEDIUM (karena delay = 14 jam)
        $this->assertEquals('MEDIUM', $shipment->delay_severity);
        $this->assertEquals(14, $shipment->delay_hours);

        // 3. TrackingEvent bertambah dengan is_exception = true
        $exceptionTracking = $shipment->trackingEvents()->where('is_exception', true)->first();
        $this->assertNotNull($exceptionTracking);
        $this->assertStringContainsString('WEATHER_DELAY', $exceptionTracking->description);
    }

    /**
     * Menyelesaikan exception ke RESOLVED wajib mengisi resolution_note dan mencatat resolved_at.
     */
    public function test_resolving_exception_requires_resolution_note_and_records_timestamp(): void
    {
        $shipment = Shipment::first();
        $exception = ShipmentException::create([
            'shipment_id' => $shipment->id,
            'type' => 'CUSTOMS_HOLD',
            'severity' => 'HIGH',
            'status' => 'OPEN',
            'delay_impact_hours' => 0,
            'description' => 'Inspection required by customs officer.',
            'reported_by' => $this->operator->id,
            'detected_at' => now(),
        ]);

        Sanctum::actingAs($this->operator);

        // 1. Gagal jika mencoba RESOLVED tanpa resolution_note
        $resFail = $this->patchJson("/api/exceptions/{$exception->id}", [
            'status' => 'RESOLVED',
            'resolution_note' => '',
        ]);
        $resFail->assertStatus(422)
            ->assertJsonValidationErrors(['resolution_note']);

        // 2. Berhasil jika resolution_note diisi
        $resSuccess = $this->patchJson("/api/exceptions/{$exception->id}", [
            'status' => 'RESOLVED',
            'resolution_note' => 'Dokumen import clearance telah diverifikasi dan disetujui bea cukai.',
        ]);

        $resSuccess->assertStatus(200)
            ->assertJsonPath('data.status', 'RESOLVED')
            ->assertJsonPath('data.resolution_note', 'Dokumen import clearance telah diverifikasi dan disetujui bea cukai.');

        $exception->refresh();
        $this->assertEquals('RESOLVED', $exception->status);
        $this->assertNotNull($exception->resolved_at);
        $this->assertEquals($this->operator->id, $exception->resolved_by);
    }

    /**
     * Format error 404 & 401 pada API mengembalikan JSON bersih tanpa membocorkan stack trace.
     */
    public function test_api_404_and_401_return_clean_json_without_stack_trace(): void
    {
        // 1. Error 401 Unauthenticated
        $res401 = $this->getJson('/api/user');
        $res401->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.',
            ])
            ->assertJsonMissing(['trace', 'file', 'line']);

        // 2. Error 404 Resource tidak ditemukan
        Sanctum::actingAs($this->admin);
        $res404 = $this->getJson('/api/shipments/999999');
        $res404->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Resource tidak ditemukan.',
            ])
            ->assertJsonMissing(['trace', 'file', 'line', 'exception']);
    }
}
