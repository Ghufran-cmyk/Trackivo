<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarrierRequest;
use App\Http\Requests\UpdateCarrierRequest;
use App\Models\AuditLog;
use App\Models\Carrier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CarrierController extends Controller
{
    /**
     * Tampilkan daftar seluruh carrier beserta status dan jumlah shipment.
     */
    public function index(): View
    {
        $carriers = Carrier::withCount('shipments')
            ->orderBy('name')
            ->paginate(15);

        return view('carriers.index', compact('carriers'));
    }

    /**
     * Form tambah carrier baru.
     */
    public function create(): View
    {
        return view('carriers.create');
    }

    /**
     * Simpan carrier baru ke database.
     */
    public function store(StoreCarrierRequest $request): RedirectResponse
    {
        $carrier = Carrier::create($request->validated());

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_CARRIER',
            'target_type' => Carrier::class,
            'target_id' => $carrier->id,
            'old_values' => null,
            'new_values' => $carrier->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('carriers.index')
            ->with('success', "Carrier '{$carrier->name}' ({$carrier->code}) berhasil ditambahkan.");
    }

    /**
     * Form edit carrier.
     */
    public function edit(Carrier $carrier): View
    {
        return view('carriers.edit', compact('carrier'));
    }

    /**
     * Perbarui data carrier di database.
     */
    public function update(UpdateCarrierRequest $request, Carrier $carrier): RedirectResponse
    {
        $oldValues = $carrier->getAttributes();
        $carrier->update($request->validated());

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_CARRIER',
            'target_type' => Carrier::class,
            'target_id' => $carrier->id,
            'old_values' => $oldValues,
            'new_values' => $carrier->fresh()->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('carriers.index')
            ->with('success', "Data carrier '{$carrier->name}' berhasil diperbarui.");
    }

    /**
     * Hapus carrier jika tidak digunakan oleh shipment.
     */
    public function destroy(Carrier $carrier): RedirectResponse
    {
        if ($carrier->shipments()->exists()) {
            return back()->with('error', "Carrier '{$carrier->name}' tidak dapat dihapus karena masih digunakan oleh {$carrier->shipments()->count()} shipment terkait.");
        }

        $oldValues = $carrier->toArray();
        $carrierName = $carrier->name;
        $carrierId = $carrier->id;
        $carrier->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_CARRIER',
            'target_type' => Carrier::class,
            'target_id' => $carrierId,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('carriers.index')
            ->with('success', "Carrier '{$carrierName}' berhasil dihapus.");
    }
}
