<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LocationResource;
use App\Models\AuditLog;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends ApiController
{
    /**
     * Daftar lokasi (semua user terautentikasi).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Location::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $query->orderBy('name');

        $perPage = (int) $request->input('per_page', 15);
        if ($perPage < 1) {
            $perPage = 15;
        } elseif ($perPage > 50) {
            $perPage = 50;
        }

        $locations = $query->paginate($perPage);

        return $this->successResponse(
            LocationResource::collection($locations),
            'Daftar lokasi berhasil diambil.',
            200,
            [
                'current_page' => $locations->currentPage(),
                'per_page' => $locations->perPage(),
                'total' => $locations->total(),
                'last_page' => $locations->lastPage(),
            ]
        );
    }

    /**
     * Tambah lokasi baru (khusus Admin).
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:locations,code'],
            'name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:PORT,AIRPORT,WAREHOUSE,CHECKPOINT'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));

        $location = Location::create($validated);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'CREATE_LOCATION',
            'target_type' => Location::class,
            'target_id' => $location->id,
            'old_values' => null,
            'new_values' => $location->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return $this->successResponse(
            new LocationResource($location),
            'Lokasi berhasil ditambahkan.',
            201
        );
    }
}
