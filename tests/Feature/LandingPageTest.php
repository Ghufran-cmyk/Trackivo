<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_landing_page_renders_successfully_for_guests(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Trackivo');
        $response->assertSee('Menggerakkan');
        $response->assertSee('bisnis maju.');
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
        $response->assertSee(route('tracking.public'));
    }

    public function test_landing_page_renders_dashboard_link_for_authenticated_users(): void
    {
        $user = User::where('email', 'customer1@trackivo.com')->first()
            ?? User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('dashboard'));
        $response->assertSee('Buka Dashboard');
    }

    public function test_landing_page_displays_operational_metrics_and_sample_tracking_chip(): void
    {
        $shipment = Shipment::first();
        $this->assertNotNull($shipment);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Pengiriman Terdata');
        $response->assertSee('Hub & Mitra Carrier', false);
        $response->assertSee('Ketepatan Pengiriman');
        $response->assertSee($shipment->tracking_number);
    }

    public function test_hero_section_renders_hero_crane_with_tilt_wrapper(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('hero-crane.jpg');
        $response->assertSee('hero-tilt-wrapper');
        $response->assertDontSee('hero-crane-bg.jpg');
        $response->assertDontSee('hero-container.png');
    }
}
