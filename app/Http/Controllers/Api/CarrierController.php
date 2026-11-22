<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CarrierResource;
use App\Models\AuditLog;
use App\Models\Carrier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarrierController extends ApiController
{
    /**
     * Daftar carrier (semua user terautentikasi).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Carrier::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $query->orderBy('name');

        $perPage = (int) $request->input('per_page', 15);
        if ($perPage < 1) {
            $perPage = 15;
        } elseif ($perPage > 50) {
            $perPage = 50;
        }

        $carriers = $query->paginate($perPage);

        return $this->successResponse(
            CarrierResource::collection($carriers),
            'Daftar carrier berhasil diambil.',
            200,
            [
                'current_page' => $carriers->currentPage(),
                'per_page' => $carriers->perPage(),
                'total' => $carriers->total(),
                'last_page' => $carriers->lastPage(),
            ]
        );
    }

    /**
     * Tambah carrier baru (khusus Admin).
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:carriers,code'],
            'name' => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active', true);

        $carrier = Carrier::create($validated);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'CREATE_CARRIER',
            'target_type' => Carrier::class,
            'target_id' => $carrier->id,
            'old_values' => null,
            'new_values' => $carrier->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return $this->successResponse(
            new CarrierResource($carrier),
            'Carrier berhasil ditambahkan.',
            201
        );
    }
}
