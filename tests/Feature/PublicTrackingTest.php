<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * a) Guest (tanpa login) dapat mengakses GET /track dan mendapat status 200.
     */
    public function test_guest_can_access_public_tracking_page(): void
    {
        $response = $this->get('/track');

        $response->assertStatus(200);
        $response->assertSee('Lacak Pengiriman Kargo Anda');
        $response->assertSee('Lacak Resi');
        $response->assertSee('Pelacakan Geospasial Interaktif');
    }

    /**
     * b) Guest yang mencari nomor resi valid melihat nomor resi, status,
     * lokasi asal/tujuan, dan riwayat tracking event, serta TIDAK melihat email pribadi customer di respons HTML.
     */
    public function test_guest_can_search_valid_tracking_number_and_customer_data_is_protected(): void
    {
        // Ambil shipment dari seeder yang memiliki relasi lengkap
        $shipment = Shipment::where('tracking_number', 'TRK-2026-JKT-RTM-001')
            ->with(['customer', 'originLocation', 'destinationLocation', 'trackingEvents'])
            ->firstOrFail();

        $customerEmail = $shipment->customer->email;
        $customerPhone = $shipment->customer->phone;
        $internalNotes = $shipment->notes;

        $response = $this->get(route('tracking.public', [
            'tracking_number' => $shipment->tracking_number,
        ]));

        $response->assertStatus(200);

        // Memastikan informasi publik shipment tampil
        $response->assertSee($shipment->tracking_number);
        $response->assertSee('STATUS: ' . $shipment->status);
        $response->assertSee($shipment->originLocation->name);
        $response->assertSee($shipment->destinationLocation->name);
        $response->assertSee($shipment->carrier->name);

        // Memastikan riwayat tracking event tampil
        $latestEvent = $shipment->trackingEvents()->latest('event_time')->first();
        if ($latestEvent) {
            $response->assertSee($latestEvent->description);
        }

        // Uji Privasi Data: TIDAK BOLEH memuat email, no telepon customer, atau catatan internal
        $response->assertDontSee($customerEmail);
        if ($customerPhone) {
            $response->assertDontSee($customerPhone);
        }
        if ($internalNotes) {
            $response->assertDontSee($internalNotes);
        }
    }

    /**
     * Uji pencarian case-insensitive dan toleransi spasi ekstra pada nomor resi.
     */
    public function test_tracking_search_is_case_insensitive_and_trims_spaces(): void
    {
        $response = $this->get(route('tracking.public', [
            'tracking_number' => '  trk-2026-jkt-rtm-001  ',
        ]));

        $response->assertStatus(200);
        $response->assertSee('TRK-2026-JKT-RTM-001');
        $response->assertSee('Tanjung Priok Port');
        $response->assertSee('Port of Rotterdam');
    }

    /**
     * c) Guest yang mencari nomor resi tidak valid tetap mendapat status 200
     * dengan pesan nomor resi tidak ditemukan.
     */
    public function test_guest_searching_invalid_tracking_number_gets_200_with_not_found_message(): void
    {
        $invalidTracking = 'TRK-NON-EXISTENT-999999';

        $response = $this->get(route('tracking.public', [
            'tracking_number' => $invalidTracking,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Nomor Resi Tidak Ditemukan');
        $response->assertSee($invalidTracking);
        $response->assertDontSee('STATUS:');
    }

    /**
     * Halaman login memuat tautan menuju pelacakan resi publik tanpa login.
     */
    public function test_login_page_contains_link_to_public_tracking(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee(route('tracking.public'));
        $response->assertSee('Lacak Resi Tanpa Login');
    }
}
