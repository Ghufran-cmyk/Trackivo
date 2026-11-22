<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Models\AuditLog;
use App\Models\Setting;
use App\Services\EtaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected EtaService $etaService
    ) {}

    /**
     * Tampilkan halaman pengaturan batas keterlambatan global (Delay Thresholds).
     */
    public function index(): View
    {
        $thresholds = $this->etaService->getThresholds();
        $lowMax = $thresholds['low_max_hours'];
        $highMin = $thresholds['high_min_hours'];

        return view('settings.index', compact('lowMax', 'highMin'));
    }

    /**
     * Simpan pembaruan pengaturan dan hitung ulang seluruh shipment aktif.
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $oldThresholds = $this->etaService->getThresholds();

        $newLow = (int) $request->validated('delay_low_max_hours');
        $newHigh = (int) $request->validated('delay_high_min_hours');

        // Simpan ke database
        Setting::set('delay_low_max_hours', (string) $newLow, 'Batas maksimal durasi delay (jam) untuk dikategorikan sebagai LOW severity');
        Setting::set('delay_high_min_hours', (string) $newHigh, 'Batas minimal durasi delay (jam) untuk dikategorikan sebagai HIGH severity');

        // Hitung ulang seluruh shipment aktif
        $recalculatedCount = $this->etaService->recalculateActiveShipments();

        // Catat ke audit_logs
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_SETTINGS',
            'target_type' => Setting::class,
            'target_id' => 0,
            'old_values' => $oldThresholds,
            'new_values' => [
                'delay_low_max_hours' => $newLow,
                'delay_high_min_hours' => $newHigh,
            ],
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('settings.index')
            ->with('success', "Konfigurasi batas keterlambatan berhasil diperbarui (LOW: ≤ {$newLow} jam, HIGH: > {$newHigh} jam). Sebanyak {$recalculatedCount} pengiriman aktif telah dihitung ulang secara otomatis.");
    }
}
