<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WeatherService
{
    /**
     * Dapatkan informasi cuaca terkini berdasarkan koordinat geospasial menggunakan Open-Meteo API.
     * Dilengkapi caching 15 menit dan penanganan kegagalan yang aman (fail-safe).
     *
     * @return array{
     *     available: bool,
     *     location_label: string,
     *     temperature_c: ?float,
     *     humidity_pct: ?int,
     *     wind_speed_kmh: ?float,
     *     weather_code: ?int,
     *     condition: string,
     *     observed_at: ?string,
     *     source: string,
     *     error: ?string
     * }
     */
    public function getCurrentWeather(?float $latitude, ?float $longitude, ?string $locationLabel = null): array
    {
        $label = $locationLabel ?: 'Lokasi Terkini';

        // Validasi ketersediaan dan batasan koordinat (-90 s.d 90, -180 s.d 180)
        if ($latitude === null || $longitude === null ||
            $latitude < -90.0 || $latitude > 90.0 ||
            $longitude < -180.0 || $longitude > 180.0) {
            return [
                'available' => false,
                'location_label' => $label,
                'temperature_c' => null,
                'humidity_pct' => null,
                'wind_speed_kmh' => null,
                'weather_code' => null,
                'condition' => 'Koordinat tidak tersedia',
                'observed_at' => null,
                'source' => 'Open-Meteo API',
                'error' => 'Koordinat geospasial belum ditentukan atau di luar jangkauan valid.',
            ];
        }

        // Cache berdasarkan koordinat yang dibulatkan 2 desimal (~1.1 km presisi)
        $roundLat = number_format($latitude, 2, '.', '');
        $roundLng = number_format($longitude, 2, '.', '');
        $cacheKey = "weather_{$roundLat}_{$roundLng}";

        // Cek cache terlebih dahulu
        $cached = Cache::get($cacheKey);
        if ($cached && is_array($cached) && ($cached['available'] ?? false) === true) {
            // Perbarui label lokasi sesuai konteks pemanggilan saat ini jika perlu
            $cached['location_label'] = $label;
            return $cached;
        } elseif ($cached) {
            Cache::forget($cacheKey);
        }

        try {
            $url = config('services.weather.url', 'https://api.open-meteo.com/v1/forecast');
            $timeout = (int) config('services.weather.timeout', 10);
            $apiKey = config('services.weather.key');

            $queryParams = [
                'latitude' => round($latitude, 4),
                'longitude' => round($longitude, 4),
                'current' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code',
                'timezone' => 'auto',
            ];

            if (! empty($apiKey)) {
                $queryParams['apikey'] = $apiKey;
            }

            $httpOptions = $this->getHttpOptions($timeout);

            $response = Http::withOptions($httpOptions)
                ->acceptJson()
                ->get($url, $queryParams);

            if ($response->failed()) {
                Log::warning('WeatherService Open-Meteo HTTP error: status ' . $response->status(), [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'lat' => $latitude,
                    'lng' => $longitude,
                ]);

                return [
                    'available' => false,
                    'location_label' => $label,
                    'temperature_c' => null,
                    'humidity_pct' => null,
                    'wind_speed_kmh' => null,
                    'weather_code' => null,
                    'condition' => 'Layanan cuaca eksternal sementara tidak dapat diakses',
                    'observed_at' => null,
                    'source' => 'Open-Meteo API',
                    'error' => "Koneksi ke Open-Meteo API mengembalikan status HTTP {$response->status()}.",
                ];
            }

            $payload = $response->json();
            $current = $payload['current'] ?? null;

            if (! is_array($current) || ! isset($current['temperature_2m'])) {
                Log::warning('WeatherService Open-Meteo payload metrics missing', [
                    'lat' => $latitude,
                    'lng' => $longitude,
                ]);

                return [
                    'available' => false,
                    'location_label' => $label,
                    'temperature_c' => null,
                    'humidity_pct' => null,
                    'wind_speed_kmh' => null,
                    'weather_code' => null,
                    'condition' => 'Data parameter cuaca tidak lengkap',
                    'observed_at' => null,
                    'source' => 'Open-Meteo API',
                    'error' => 'Respons data cuaca tidak memuat parameter saat ini (current metrics).',
                ];
            }

            $weatherCode = isset($current['weather_code']) ? (int) $current['weather_code'] : null;
            $condition = $this->mapWeatherCodeToText($weatherCode);

            $observedTime = null;
            if (! empty($current['time'])) {
                try {
                    $observedTime = Carbon::parse($current['time'])->format('d M Y, H:i');
                } catch (Throwable) {
                    $observedTime = now()->format('d M Y, H:i');
                }
            } else {
                $observedTime = now()->format('d M Y, H:i');
            }

            $result = [
                'available' => true,
                'location_label' => $label,
                'temperature_c' => isset($current['temperature_2m']) ? (float) $current['temperature_2m'] : null,
                'humidity_pct' => isset($current['relative_humidity_2m']) ? (int) $current['relative_humidity_2m'] : null,
                'wind_speed_kmh' => isset($current['wind_speed_10m']) ? (float) $current['wind_speed_10m'] : null,
                'weather_code' => $weatherCode,
                'condition' => $condition,
                'observed_at' => $observedTime,
                'source' => 'Open-Meteo API',
                'error' => null,
            ];

            // Cache data cuaca selama 15 menit hanya jika pemanggilan API berhasil
            if (($result['available'] ?? false) === true) {
                Cache::put($cacheKey, $result, now()->addMinutes(15));
            }

            return $result;

        } catch (Throwable $e) {
            Log::warning('WeatherService exception: ' . $e->getMessage(), [
                'lat' => $latitude,
                'lng' => $longitude,
            ]);

            return [
                'available' => false,
                'location_label' => $label,
                'temperature_c' => null,
                'humidity_pct' => null,
                'wind_speed_kmh' => null,
                'weather_code' => null,
                'condition' => 'Gagal terhubung ke penyedia cuaca',
                'observed_at' => null,
                'source' => 'Open-Meteo API',
                'error' => 'Koneksi ke penyedia cuaca eksternal mengalami timeout atau gangguan jaringan.',
            ];
        }
    }

    /**
     * Dapatkan opsi HTTP client termasuk timeout, resolusi IPv4, dan verifikasi SSL.
     *
     * @return array<string|int, mixed>
     */
    protected function getHttpOptions(int $timeout): array
    {
        $options = [
            'timeout' => $timeout,
            'connect_timeout' => $timeout,
            'force_ip_resolve' => 'v4',
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ];

        $verifySsl = config('services.http_client.verify_ssl', true);
        $caBundle = config('services.http_client.ca_bundle');

        if (! empty($caBundle) && file_exists($caBundle)) {
            $options['verify'] = $caBundle;
        } elseif ($verifySsl === false || $verifySsl === 'false' || $verifySsl === 0 || $verifySsl === '0') {
            $options['verify'] = false;
        } else {
            // Auto-detect bundle CA di Windows/Laragon jika php.ini belum mengonfigurasinya
            $laragonCa = 'C:/laragon/etc/ssl/cacert.pem';
            $gitCa = 'C:/Program Files/Git/usr/ssl/certs/ca-bundle.crt';

            if (file_exists($laragonCa)) {
                $options['verify'] = $laragonCa;
            } elseif (file_exists($gitCa)) {
                $options['verify'] = $gitCa;
            } elseif (! empty(ini_get('curl.cainfo')) && file_exists(ini_get('curl.cainfo'))) {
                $options['verify'] = ini_get('curl.cainfo');
            } elseif (! empty(ini_get('openssl.cafile')) && file_exists(ini_get('openssl.cafile'))) {
                $options['verify'] = ini_get('openssl.cafile');
            }
        }

        return $options;
    }

    /**
     * Petakan kode WMO (World Meteorological Organization) ke deskripsi Bahasa Indonesia.
     */
    public function mapWeatherCodeToText(?int $code): string
    {
        if ($code === null) {
            return 'Tidak Diketahui';
        }

        return match ($code) {
            0 => 'Cerah',
            1 => 'Cerah Berawan',
            2 => 'Sebagian Berawan',
            3 => 'Berawan Tebal',
            45 => 'Berkabut',
            48 => 'Kabut Tebal Mengendap',
            51 => 'Gerimis Ringan',
            53 => 'Gerimis Sedang',
            55 => 'Gerimis Lebat',
            56, 57 => 'Gerimis Beku',
            61 => 'Hujan Ringan',
            63 => 'Hujan Sedang',
            65 => 'Hujan Deras',
            66, 67 => 'Hujan Beku',
            71 => 'Salju Ringan',
            73 => 'Salju Sedang',
            75 => 'Salju Lebat',
            77 => 'Butiran Salju',
            80 => 'Hujan Rintik-Rintik',
            81 => 'Hujan Sedang',
            82 => 'Hujan Deras / Badai Hujan',
            85, 86 => 'Hujan Salju',
            95 => 'Badai Petir',
            96, 99 => 'Badai Petir disertai Hujan Es',
            default => 'Kondisi Cuaca Bervariasi (' . $code . ')',
        };
    }
}
