<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GeocodingService
{
    /**
     * Cari koordinat geospasial (latitude, longitude) berdasarkan nama tempat/kota/pelabuhan/bandara
     * menggunakan Nominatim OpenStreetMap API.
     *
     * @return array{
     *     success: bool,
     *     results: array<int, array{
     *         display_name: string,
     *         latitude: float,
     *         longitude: float,
     *         country: string,
     *         type: string
     *     }>,
     *     error: ?string
     * }
     */
    public function search(string $query, int $limit = 5): array
    {
        $cleanQuery = trim($query);

        if (mb_strlen($cleanQuery) < 3) {
            return [
                'success' => false,
                'results' => [],
                'error' => 'Masukkan minimal 3 karakter nama kota, pelabuhan, atau bandara.',
            ];
        }

        try {
            $url = config('services.geocoding.url', 'https://nominatim.openstreetmap.org/search');
            $userAgent = config('services.geocoding.user_agent', 'TrackivoGlobalLogistics/1.0 (contact@trackivo.com)');
            $timeout = (int) config('services.geocoding.timeout', 10);

            $httpOptions = $this->getHttpOptions($timeout);

            $response = Http::withOptions($httpOptions)
                ->withHeaders([
                    'User-Agent' => $userAgent,
                    'Accept-Language' => 'id,en',
                ])
                ->acceptJson()
                ->get($url, [
                    'q' => $cleanQuery,
                    'format' => 'json',
                    'limit' => max(1, min($limit, 10)),
                    'addressdetails' => 1,
                ]);

            if ($response->failed()) {
                Log::warning('GeocodingService Nominatim HTTP error: status ' . $response->status(), [
                    'status' => $response->status(),
                    'query' => $cleanQuery,
                ]);

                return [
                    'success' => false,
                    'results' => [],
                    'error' => "Layanan geocoding mengembalikan respons HTTP {$response->status()}.",
                ];
            }

            $data = $response->json();

            if (! is_array($data) || empty($data)) {
                return [
                    'success' => true,
                    'results' => [],
                    'error' => null,
                ];
            }

            $results = [];
            foreach ($data as $item) {
                if (! isset($item['lat'], $item['lon'])) {
                    continue;
                }

                $address = $item['address'] ?? [];
                $country = $address['country'] ?? ($address['country_code'] ?? 'Unknown');
                $type = $item['type'] ?? ($item['class'] ?? 'place');

                $results[] = [
                    'display_name' => $item['display_name'] ?? 'Lokasi Tanpa Nama',
                    'latitude' => (float) $item['lat'],
                    'longitude' => (float) $item['lon'],
                    'country' => (string) $country,
                    'type' => strtoupper((string) $type),
                ];
            }

            return [
                'success' => true,
                'results' => $results,
                'error' => null,
            ];

        } catch (Throwable $e) {
            Log::warning('GeocodingService exception: ' . $e->getMessage(), [
                'query' => $cleanQuery,
            ]);

            return [
                'success' => false,
                'results' => [],
                'error' => 'Koneksi ke OpenStreetMap Geocoding mengalami batas waktu (timeout) atau gangguan jaringan.',
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
}
