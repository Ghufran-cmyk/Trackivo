<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Carrier;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Models\ShipmentItem;
use App\Models\TrackingEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data.
     */
    public function run(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        // ==========================================
        // 1. Akun Pengguna Demo (4 Akun, Password: password)
        // ==========================================
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@trackivo.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'company_name' => 'Trackivo Global Operations',
            'phone' => '+62 21 555-0100',
            'email_verified_at' => $now,
        ]);

        $operator = User::create([
            'name' => 'Operations Specialist',
            'email' => 'operator@trackivo.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'company_name' => 'Trackivo Control Tower',
            'phone' => '+62 21 555-0101',
            'email_verified_at' => $now,
        ]);

        $customer1 = User::create([
            'name' => 'Ghufran',
            'email' => 'customer1@trackivo.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'company_name' => 'PT Nusantara Logistik',
            'phone' => '+62 811-2345-6789',
            'email_verified_at' => $now,
        ]);

        $customer2 = User::create([
            'name' => 'Fadlur',
            'email' => 'customer2@trackivo.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'company_name' => 'Pacific Trade Co.',
            'phone' => '+1 415-555-2671',
            'email_verified_at' => $now,
        ]);

        // ==========================================
        // 2. Pengaturan Global (Tabel Settings)
        // ==========================================
        Setting::create([
            'key' => 'delay_low_max_hours',
            'value' => '6',
            'description' => 'Batas maksimal durasi delay (jam) untuk dikategorikan sebagai LOW severity',
        ]);

        Setting::create([
            'key' => 'delay_high_min_hours',
            'value' => '24',
            'description' => 'Batas minimal durasi delay (jam) untuk dikategorikan sebagai HIGH severity',
        ]);

        // ==========================================
        // 3. 4 Global Carriers (Laut & Udara)
        // ==========================================
        $carrierMaersk = Carrier::create([
            'code' => 'MAEU',
            'name' => 'Maersk Line',
            'contact_email' => 'operations@maersk.com',
            'contact_phone' => '+45 33 63 33 63',
            'is_active' => true,
        ]);

        $carrierMsc = Carrier::create([
            'code' => 'MSCU',
            'name' => 'Mediterranean Shipping Company (MSC)',
            'contact_email' => 'support@msc.com',
            'contact_phone' => '+41 22 703 8888',
            'is_active' => true,
        ]);

        $carrierSia = Carrier::create([
            'code' => 'SQ-CARGO',
            'name' => 'Singapore Airlines Cargo',
            'contact_email' => 'cargo_desk@singaporeair.com',
            'contact_phone' => '+65 6542 5588',
            'is_active' => true,
        ]);

        $carrierLufthansa = Carrier::create([
            'code' => 'LH-CARGO',
            'name' => 'Lufthansa Cargo AG',
            'contact_email' => 'service@lufthansa-cargo.com',
            'contact_phone' => '+49 69 6960',
            'is_active' => true,
        ]);

        // ==========================================
        // 4. 6 Locations Lintas Benua (Koordinat Asli)
        // ==========================================
        $locJakarta = Location::create([
            'code' => 'ID-JKT-TPP',
            'name' => 'Tanjung Priok Port',
            'country' => 'Indonesia',
            'type' => 'PORT',
            'latitude' => -6.1025000,
            'longitude' => 106.8833000,
        ]);

        $locSingapore = Location::create([
            'code' => 'SG-SIN-PSA',
            'name' => 'Port of Singapore (PSA)',
            'country' => 'Singapore',
            'type' => 'PORT',
            'latitude' => 1.2644000,
            'longitude' => 103.8222000,
        ]);

        $locRotterdam = Location::create([
            'code' => 'NL-RTM-PORT',
            'name' => 'Port of Rotterdam',
            'country' => 'Netherlands',
            'type' => 'PORT',
            'latitude' => 51.9054000,
            'longitude' => 4.3160000,
        ]);

        $locLAX = Location::create([
            'code' => 'US-LAX-AIR',
            'name' => 'Los Angeles International Airport (LAX)',
            'country' => 'United States',
            'type' => 'AIRPORT',
            'latitude' => 33.9416000,
            'longitude' => -118.4085000,
        ]);

        $locSydney = Location::create([
            'code' => 'AU-SYD-PORT',
            'name' => 'Port Botany (Sydney)',
            'country' => 'Australia',
            'type' => 'PORT',
            'latitude' => -33.9717000,
            'longitude' => 151.2189000,
        ]);

        $locFrankfurt = Location::create([
            'code' => 'DE-FRA-AIR',
            'name' => 'Frankfurt Airport Cargo City (FRA)',
            'country' => 'Germany',
            'type' => 'AIRPORT',
            'latitude' => 50.0379000,
            'longitude' => 8.5622000,
        ]);

        // ==========================================
        // 5. 4 Shipments Rute Internasional
        // ==========================================

        // Shipment 1: IN_TRANSIT (Tepat Waktu / On-Time)
        $shipment1 = Shipment::create([
            'tracking_number' => 'TRK-2026-JKT-RTM-001',
            'invoice_number' => 'INV-2026-000001',
            'customer_id' => $customer1->id,
            'carrier_id' => $carrierMaersk->id,
            'origin_location_id' => $locJakarta->id,
            'destination_location_id' => $locRotterdam->id,
            'transport_mode' => 'OCEAN',
            'cargo_type' => 'Tekstil & Pakaian Jadi (Garments)',
            'weight_kg' => 18500.00,
            'volume_cbm' => 48.50,
            'base_cost' => 750000,
            'weight_volume_cost' => 39875000, // (18500 * 1500) + (48.5 * 250000) = 27750000 + 12125000
            'insurance_cost' => 250000,
            'total_amount' => 40875000,
            'payment_method' => 'CORPORATE_INVOICE',
            'payment_status' => 'PAID',
            'paid_at' => (clone $now)->subDays(5)->setHour(10),
            'payment_confirmed_by' => $admin->id,
            'status' => 'IN_TRANSIT',
            'expected_departure' => (clone $now)->subDays(5),
            'expected_arrival' => (clone $now)->addDays(12),
            'estimated_arrival' => (clone $now)->addDays(12),
            'delay_hours' => 0,
            'delay_severity' => 'NONE',
            'notes' => 'Kontainer 40ft High Cube. Rute pelayaran normal melintasi Selat Malaka menuju Terusan Suez.',
            'created_by' => $admin->id,
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment1->id,
            'item_name' => 'Gulungan Kain Katun Premium',
            'quantity' => 320,
            'weight_kg' => 11000.00,
            'description' => 'Roll katun 100% grade export',
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment1->id,
            'item_name' => 'Pakaian Jadi Siap Ekspor',
            'quantity' => 450,
            'weight_kg' => 7500.00,
            'description' => 'Karton boks pakaian siap edar di retail Eropa',
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment1->id,
            'location_id' => $locJakarta->id,
            'status' => 'PICKED_UP',
            'is_exception' => false,
            'latitude' => $locJakarta->latitude,
            'longitude' => $locJakarta->longitude,
            'event_time' => (clone $now)->subDays(5)->setHour(8),
            'description' => 'Kontainer dimuat di gudang logistik Jakarta dan menuju pelabuhan.',
            'created_by' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment1->id,
            'location_id' => $locJakarta->id,
            'status' => 'LOADED',
            'is_exception' => false,
            'latitude' => $locJakarta->latitude,
            'longitude' => $locJakarta->longitude,
            'event_time' => (clone $now)->subDays(5)->setHour(18),
            'description' => 'Bea cukai selesai, kontainer dinaikkan ke kapal Maersk Mc-Kinney Moller.',
            'created_by' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment1->id,
            'location_id' => $locSingapore->id,
            'status' => 'IN_TRANSIT',
            'is_exception' => false,
            'latitude' => $locSingapore->latitude,
            'longitude' => $locSingapore->longitude,
            'event_time' => (clone $now)->subDays(2)->setHour(14),
            'description' => 'Kapal singgah di Port of Singapore untuk bunkering dan melanjutkan pelayaran.',
            'created_by' => $operator->id,
        ]);


        // Shipment 2: IN_TRANSIT (Delayed, High Severity + Exception)
        $shipment2 = Shipment::create([
            'tracking_number' => 'TRK-2026-SIN-FRA-002',
            'invoice_number' => 'INV-2026-000002',
            'customer_id' => $customer2->id,
            'carrier_id' => $carrierSia->id,
            'origin_location_id' => $locSingapore->id,
            'destination_location_id' => $locFrankfurt->id,
            'transport_mode' => 'AIR',
            'cargo_type' => 'Komponen Semikonduktor & Mikroelektronika',
            'weight_kg' => 4200.00,
            'volume_cbm' => 14.80,
            'base_cost' => 1500000,
            'weight_volume_cost' => 44580000, // (4200 * 8500) + (14.8 * 600000) = 35700000 + 8880000
            'insurance_cost' => 500000,
            'total_amount' => 46580000,
            'payment_method' => 'BANK_TRANSFER',
            'payment_status' => 'PAID',
            'paid_at' => (clone $now)->subDays(3)->setHour(11),
            'payment_confirmed_by' => $operator->id,
            'status' => 'IN_TRANSIT',
            'expected_departure' => (clone $now)->subDays(3),
            'expected_arrival' => (clone $now)->subDay(),
            'estimated_arrival' => (clone $now)->addHours(6),
            'delay_hours' => 30,
            'delay_severity' => 'HIGH',
            'notes' => 'Penerbangan kargo dialihkan rutenya akibat siklon tropis ekstrem di koridor Asia Selatan.',
            'created_by' => $operator->id,
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment2->id,
            'item_name' => 'Automotive Microcontroller Chips',
            'quantity' => 12000,
            'weight_kg' => 2400.00,
            'description' => 'Kemasan antistatis dengan segel suhu terkontrol',
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment2->id,
            'item_name' => 'Printed Circuit Board Assemblies (PCBA)',
            'quantity' => 500,
            'weight_kg' => 1800.00,
            'description' => 'Modul elektronik industri otomotif',
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment2->id,
            'location_id' => $locSingapore->id,
            'status' => 'PICKED_UP',
            'is_exception' => false,
            'latitude' => $locSingapore->latitude,
            'longitude' => $locSingapore->longitude,
            'event_time' => (clone $now)->subDays(3)->setHour(10),
            'description' => 'Kargo diterima di Singapore Changi Airfreight Centre.',
            'created_by' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment2->id,
            'location_id' => $locSingapore->id,
            'status' => 'EXCEPTION_WEATHER',
            'is_exception' => true,
            'latitude' => $locSingapore->latitude,
            'longitude' => $locSingapore->longitude,
            'event_time' => (clone $now)->subDays(2)->setHour(12),
            'description' => 'Penerbangan ditahan akibat badai siklon tropis di rute penerbangan.',
            'created_by' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment2->id,
            'location_id' => $locFrankfurt->id,
            'status' => 'IN_TRANSIT',
            'is_exception' => false,
            'latitude' => 30.5000000,
            'longitude' => 55.2000000,
            'event_time' => (clone $now)->subHours(8),
            'description' => 'Penerbangan pengganti SQ-7922 sedang melintasi koridor udara Timur Tengah.',
            'created_by' => $operator->id,
        ]);

        // Exception 1 (Open)
        ShipmentException::create([
            'shipment_id' => $shipment2->id,
            'type' => 'WEATHER_DELAY',
            'severity' => 'HIGH',
            'status' => 'OPEN',
            'delay_impact_hours' => 30,
            'description' => 'Badai taifun hebat menutup bandara hub transit kargo, menyebabkan ground-stop selama 30 jam.',
            'resolution_note' => 'Operator sedang berkoordinasi dengan tim ground handling Frankfurt untuk slot prioritas pendaratan.',
            'detected_at' => (clone $now)->subDays(2)->setHour(12),
            'reported_by' => $operator->id,
        ]);


        // Shipment 3: BOOKED (Status BOOKED)
        $shipment3 = Shipment::create([
            'tracking_number' => 'TRK-2026-JKT-LAX-003',
            'invoice_number' => 'INV-2026-000003',
            'customer_id' => $customer1->id,
            'carrier_id' => $carrierMsc->id,
            'origin_location_id' => $locJakarta->id,
            'destination_location_id' => $locLAX->id,
            'transport_mode' => 'OCEAN',
            'cargo_type' => 'Mebel Kayu Jati & Kerajinan Ramah Lingkungan',
            'weight_kg' => 14200.00,
            'volume_cbm' => 52.00,
            'base_cost' => 750000,
            'weight_volume_cost' => 34300000, // (14200 * 1500) + (52.0 * 250000) = 21300000 + 13000000
            'insurance_cost' => 0,
            'total_amount' => 35050000,
            'payment_method' => 'BANK_TRANSFER',
            'payment_status' => 'UNPAID', // Dibiarkan UNPAID agar tombol konfirmasi bisa didemokan
            'paid_at' => null,
            'payment_confirmed_by' => null,
            'status' => 'BOOKED',
            'expected_departure' => (clone $now)->addDays(3),
            'expected_arrival' => (clone $now)->addDays(25),
            'estimated_arrival' => (clone $now)->addDays(25),
            'delay_hours' => 0,
            'delay_severity' => 'NONE',
            'notes' => 'Pemesanan slot kontainer 40ft High Cube telah terkonfirmasi. Jadwal penyerahan depo Priok.',
            'created_by' => $admin->id,
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment3->id,
            'item_name' => 'Meja Makan Kayu Jati Solid',
            'quantity' => 85,
            'weight_kg' => 8500.00,
            'description' => 'Peti kayu pelindung berstandar ISPM-15',
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment3->id,
            'item_name' => 'Kursi Santai Rotan Alami',
            'quantity' => 140,
            'weight_kg' => 5700.00,
            'description' => 'Karton boks pelindung kelembapan tinggi',
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment3->id,
            'location_id' => $locJakarta->id,
            'status' => 'BOOKED',
            'is_exception' => false,
            'latitude' => $locJakarta->latitude,
            'longitude' => $locJakarta->longitude,
            'event_time' => (clone $now)->subDays(1),
            'description' => 'Booking dikonfirmasi oleh MSC untuk jadwal pelayaran Trans-Pasifik.',
            'created_by' => $admin->id,
        ]);


        // Shipment 4: DELIVERED (Delivered + Resolved Exception)
        $shipment4 = Shipment::create([
            'tracking_number' => 'TRK-2026-FRA-SYD-004',
            'invoice_number' => 'INV-2026-000004',
            'customer_id' => $customer2->id,
            'carrier_id' => $carrierLufthansa->id,
            'origin_location_id' => $locFrankfurt->id,
            'destination_location_id' => $locSydney->id,
            'transport_mode' => 'AIR',
            'cargo_type' => 'Peralatan Diagnostik Medis Presisi Tinggi',
            'weight_kg' => 2800.00,
            'volume_cbm' => 9.60,
            'base_cost' => 1500000,
            'weight_volume_cost' => 29560000, // (2800 * 8500) + (9.6 * 600000) = 23800000 + 5760000
            'insurance_cost' => 750000,
            'total_amount' => 31810000,
            'payment_method' => 'QRIS',
            'payment_status' => 'PAID',
            'paid_at' => (clone $now)->subDays(10)->setHour(12),
            'payment_confirmed_by' => $operator->id,
            'status' => 'DELIVERED',
            'expected_departure' => (clone $now)->subDays(10),
            'expected_arrival' => (clone $now)->subDays(4),
            'estimated_arrival' => (clone $now)->subDays(3),
            'delay_hours' => 12,
            'delay_severity' => 'MEDIUM',
            'notes' => 'Barang bernilai tinggi. Telah diterima dan ditandatangani oleh Sydney Health Centre.',
            'created_by' => $operator->id,
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment4->id,
            'item_name' => 'Mesin Ultrasound Doppler 4D',
            'quantity' => 12,
            'weight_kg' => 1600.00,
            'description' => 'Dilengkapi shock sensor dan tilt indicator',
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment4->id,
            'item_name' => 'Modul Instrumen Bedah Presisi',
            'quantity' => 30,
            'weight_kg' => 1200.00,
            'description' => 'Kemasan steril segel ganda',
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment4->id,
            'location_id' => $locFrankfurt->id,
            'status' => 'PICKED_UP',
            'is_exception' => false,
            'latitude' => $locFrankfurt->latitude,
            'longitude' => $locFrankfurt->longitude,
            'event_time' => (clone $now)->subDays(10)->setHour(9),
            'description' => 'Kargo diterima di Frankfurt CargoCity South.',
            'created_by' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment4->id,
            'location_id' => $locSydney->id,
            'status' => 'CUSTOMS_INSPECTION',
            'is_exception' => true,
            'latitude' => $locSydney->latitude,
            'longitude' => $locSydney->longitude,
            'event_time' => (clone $now)->subDays(5)->setHour(11),
            'description' => 'Tertahan sementara pada pemeriksaan karantina dan kelengkapan sertifikat alat medis.',
            'created_by' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment4->id,
            'location_id' => $locSydney->id,
            'status' => 'DELIVERED',
            'is_exception' => false,
            'latitude' => $locSydney->latitude,
            'longitude' => $locSydney->longitude,
            'event_time' => (clone $now)->subDays(3)->setHour(15),
            'description' => 'Paket berhasil diterima dan ditandatangani oleh penerima kargo di Sydney.',
            'created_by' => $operator->id,
        ]);

        // Exception 2 (Resolved)
        ShipmentException::create([
            'shipment_id' => $shipment4->id,
            'type' => 'CUSTOMS_HOLD',
            'severity' => 'MEDIUM',
            'status' => 'RESOLVED',
            'delay_impact_hours' => 12,
            'description' => 'Petugas bea cukai Australia meminta surat sertifikasi kesesuaian alat medis tambahan.',
            'resolution_note' => 'Dokumen tambahan dari produsen berhasil diunggah dan disetujui oleh Australian Border Force.',
            'detected_at' => (clone $now)->subDays(5)->setHour(11),
            'reported_by' => $operator->id,
            'resolved_by' => $admin->id,
            'resolved_at' => (clone $now)->subDays(4)->setHour(14),
        ]);


        // ==========================================
        // 6. Notifikasi Awal (Customer)
        // ==========================================
        Notification::create([
            'user_id' => $customer1->id,
            'shipment_id' => $shipment1->id,
            'title' => 'Update Posisi: TRK-2026-JKT-RTM-001',
            'message' => 'Kapal pengangkut kargo garmen Anda telah melewati Selat Malaka dalam kondisi aman dan tepat waktu.',
            'is_read' => false,
            'read_at' => null,
        ]);

        Notification::create([
            'user_id' => $customer1->id,
            'shipment_id' => $shipment3->id,
            'title' => 'Booking Terkonfirmasi: TRK-2026-JKT-LAX-003',
            'message' => 'Booking kontainer kargo mebel Anda telah disetujui carrier MSC untuk keberangkatan 3 hari mendatang.',
            'is_read' => true,
            'read_at' => (clone $now)->subHours(12),
        ]);

        Notification::create([
            'user_id' => $customer2->id,
            'shipment_id' => $shipment2->id,
            'title' => 'Peringatan Delay Kargo: TRK-2026-SIN-FRA-002',
            'message' => 'Penerbangan kargo mikroelektronika Anda mengalami keterlambatan +30 jam akibat cuaca taifun ekstrem.',
            'is_read' => false,
            'read_at' => null,
        ]);

        Notification::create([
            'user_id' => $customer2->id,
            'shipment_id' => $shipment4->id,
            'title' => 'Kargo Berhasil Diterima: TRK-2026-FRA-SYD-004',
            'message' => 'Pengiriman peralatan medis ke Sydney telah selesai diserahkan dan ditandatangani oleh penerima.',
            'is_read' => true,
            'read_at' => (clone $now)->subDays(2),
        ]);

        // ==========================================
        // 7. Initial Audit Log
        // ==========================================
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'SYSTEM_INITIALIZATION',
            'target_type' => 'DatabaseSeeder',
            'target_id' => 1,
            'old_values' => null,
            'new_values' => [
                'platform' => 'Trackivo',
                'phase' => 'Tahap 1',
                'tables_initialized' => 10,
                'timezone' => 'Asia/Jakarta',
            ],
            'ip_address' => '127.0.0.1',
        ]);
    }
}
