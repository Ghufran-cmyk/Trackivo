<?php

namespace Tests\Unit;

use App\Services\GeocodingService;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExternalApiFallbackTest extends TestCase
{
    protected WeatherService $weatherService;
    protected GeocodingService $geocodingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->weatherService = app(WeatherService::class);
        $this->geocodingService = app(GeocodingService::class);
        Cache::flush();
    }

    /**
     * WeatherService mengembalikan data cuaca valid ketika Open-Meteo API berhasil dihubungi.
     */
    public function test_weather_service_success_response(): void
    {
        Http::fake([
            'https://api.open-meteo.com/*' => Http::response([
                'current' => [
                    'temperature_2m' => 29.4,
                    'relative_humidity_2m' => 78,
                    'wind_speed_10m' => 14.2,
                    'weather_code' => 1,
                    'time' => '2026-11-22T14:00',
                ],
            ], 200),
        ]);

        $weather = $this->weatherService->getCurrentWeather(-6.1018, 106.8833, 'Pelabuhan Tanjung Priok');

        $this->assertTrue($weather['available']);
        $this->assertEquals(29.4, $weather['temperature_c']);
        $this->assertEquals(78, $weather['humidity_pct']);
        $this->assertEquals(14.2, $weather['wind_speed_kmh']);
        $this->assertEquals('Cerah Berawan', $weather['condition']);
        $this->assertNull($weather['error']);
    }

    /**
     * WeatherService menangani error 500 atau timeout dari external API tanpa melempar exception
     * dan mengembalikan fallback available=false yang aman.
     */
    public function test_weather_service_server_error_and_timeout_fallback(): void
    {
        // 1. Uji respons HTTP 500
        Http::fake([
            'https://api.open-meteo.com/*' => Http::response('Internal Server Error', 500),
        ]);

        $weather500 = $this->weatherService->getCurrentWeather(-6.1018, 106.8833, 'Pelabuhan Tanjung Priok');

        $this->assertFalse($weather500['available']);
        $this->assertNull($weather500['temperature_c']);
        $this->assertNotNull($weather500['error']);

        // 2. Uji kondisi connection timeout / throw exception
        Cache::flush();
        Http::fake([
            'https://api.open-meteo.com/*' => function () {
                throw new \Illuminate\Http\Client\ConnectionException('Connection timed out after 10000ms');
            },
        ]);

        $weatherTimeout = $this->weatherService->getCurrentWeather(-6.1018, 106.8833, 'Pelabuhan Tanjung Priok');

        $this->assertFalse($weatherTimeout['available']);
        $this->assertNull($weatherTimeout['temperature_c']);
        $this->assertStringContainsString('koneksi', strtolower($weatherTimeout['error']));
    }

    /**
     * GeocodingService mengembalikan hasil koordinat valid ketika OpenStreetMap Nominatim sukses.
     */
    public function test_geocoding_service_success_response(): void
    {
        Http::fake([
            'https://nominatim.openstreetmap.org/*' => Http::response([
                [
                    'display_name' => 'Tanjung Priok, Jakarta Utara, DKI Jakarta, Indonesia',
                    'lat' => '-6.1018',
                    'lon' => '106.8833',
                    'type' => 'harbour',
                    'address' => [
                        'country' => 'Indonesia',
                    ],
                ],
            ], 200),
        ]);

        $result = $this->geocodingService->search('Tanjung Priok');

        $this->assertTrue($result['success']);
        $this->assertCount(1, $result['results']);
        $this->assertEquals(-6.1018, $result['results'][0]['latitude']);
        $this->assertEquals(106.8833, $result['results'][0]['longitude']);
        $this->assertEquals('Indonesia', $result['results'][0]['country']);
    }

    /**
     * GeocodingService menangani error 500 atau timeout tanpa melempar exception
     * dan mengembalikan success=false dengan array results kosong.
     */
    public function test_geocoding_service_server_error_and_timeout_fallback(): void
    {
        // 1. Uji HTTP 500
        Http::fake([
            'https://nominatim.openstreetmap.org/*' => Http::response('Internal Server Error', 500),
        ]);

        $res500 = $this->geocodingService->search('Tanjung Priok');

        $this->assertFalse($res500['success']);
        $this->assertEmpty($res500['results']);
        $this->assertNotNull($res500['error']);

        // 2. Uji timeout / connection exception
        Http::fake([
            'https://nominatim.openstreetmap.org/*' => function () {
                throw new \Illuminate\Http\Client\ConnectionException('Connection timed out');
            },
        ]);

        $resTimeout = $this->geocodingService->search('Tanjung Priok');

        $this->assertFalse($resTimeout['success']);
        $this->assertEmpty($resTimeout['results']);
        $this->assertNotNull($resTimeout['error']);
    }
}
