<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\AuditLog;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    /**
     * Tampilkan daftar seluruh lokasi beserta kode unik, tipe, negara, dan koordinat.
     */
    public function index(): View
    {
        $locations = Location::withCount(['originShipments', 'destinationShipments'])
            ->orderBy('country')
            ->orderBy('name')
            ->paginate(15);

        return view('locations.index', compact('locations'));
    }

    /**
     * Form tambah lokasi baru.
     */
    public function create(): View
    {
        return view('locations.create');
    }

    /**
     * Simpan lokasi baru ke database.
     */
    public function store(StoreLocationRequest $request): RedirectResponse
    {
        $location = Location::create($request->validated());

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_LOCATION',
            'target_type' => Location::class,
            'target_id' => $location->id,
            'old_values' => null,
            'new_values' => $location->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('locations.index')
            ->with('success', "Lokasi '{$location->name}' ({$location->code}) berhasil ditambahkan.");
    }

    /**
     * Form edit lokasi.
     */
    public function edit(Location $location): View
    {
        return view('locations.edit', compact('location'));
    }

    /**
     * Perbarui data lokasi di database.
     */
    public function update(UpdateLocationRequest $request, Location $location): RedirectResponse
    {
        $oldValues = $location->getAttributes();
        $location->update($request->validated());

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_LOCATION',
            'target_type' => Location::class,
            'target_id' => $location->id,
            'old_values' => $oldValues,
            'new_values' => $location->fresh()->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('locations.index')
            ->with('success', "Data lokasi '{$location->name}' berhasil diperbarui.");
    }

    /**
     * Hapus lokasi jika tidak digunakan sebagai origin atau destination shipment.
     */
    public function destroy(Location $location): RedirectResponse
    {
        $originCount = $location->originShipments()->count();
        $destCount = $location->destinationShipments()->count();

        if ($originCount > 0 || $destCount > 0) {
            return back()->with('error', "Lokasi '{$location->name}' tidak dapat dihapus karena masih digunakan sebagai titik asal ({$originCount} shipment) atau tujuan ({$destCount} shipment).");
        }

        $oldValues = $location->toArray();
        $locationName = $location->name;
        $locationId = $location->id;
        $location->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_LOCATION',
            'target_type' => Location::class,
            'target_id' => $locationId,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('locations.index')
            ->with('success', "Lokasi '{$locationName}' berhasil dihapus.");
    }
}
