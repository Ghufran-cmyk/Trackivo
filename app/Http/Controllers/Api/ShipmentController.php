<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ShipmentResource;
use App\Http\Resources\TrackingEventResource;
use App\Models\Shipment;
use App\Models\User;
use App\Services\EtaService;
use App\Services\ShipmentService;
use App\Services\TrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ShipmentController extends ApiController
{
    public function __construct(
        protected ShipmentService $shipmentService,
        protected TrackingService $trackingService,
        protected EtaService $etaService
    ) {}

    /**
     * Daftar pengiriman kargo (Customer terbatas miliknya, Admin & Operator melihat semua).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Customer HANYA melihat pengiriman miliknya sendiri
        $query = $user->isCustomer()
            ? Shipment::where('customer_id', $user->id)
            : Shipment::query();

        // Pencarian nomor resi atau jenis kargo
        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('cargo_type', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter carrier
        if ($request->filled('carrier_id')) {
            $query->where('carrier_id', $request->carrier_id);
        }

        // Filter titik asal
        if ($request->filled('origin_location_id')) {
            $query->where('origin_location_id', $request->origin_location_id);
        }

        // Filter titik tujuan
        if ($request->filled('destination_location_id')) {
            $query->where('destination_location_id', $request->destination_location_id);
        }

        // Filter tanggal keberangkatan
        if ($request->filled('date_from')) {
            $query->whereDate('expected_departure', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expected_departure', '<=', $request->date_to);
        }

        // Filter kondisi delay
        if ($request->filled('delay_status')) {
            $delayStatus = $request->delay_status;
            if ($delayStatus === 'on_time') {
                $query->where('delay_hours', '<=', 0)
                      ->where('delay_severity', 'NONE');
            } elseif ($delayStatus === 'delayed') {
                $query->where(function ($q) {
                    $q->where('delay_hours', '>', 0)
                      ->orWhere('delay_severity', '!=', 'NONE');
                });
            } elseif (in_array($delayStatus, ['LOW', 'MEDIUM', 'HIGH'], true)) {
                $query->where('delay_severity', $delayStatus);
            }
        }

        // Pengurutan (Sorting)
        $sort = $request->input('sort', 'latest');
        $allowedSorts = [
            'latest'     => ['column' => 'created_at', 'direction' => 'desc'],
            'eta_asc'    => ['column' => 'estimated_arrival', 'direction' => 'asc'],
            'eta_desc'   => ['column' => 'estimated_arrival', 'direction' => 'desc'],
            'delay_desc' => ['column' => 'delay_hours', 'direction' => 'desc'],
            'number_asc' => ['column' => 'tracking_number', 'direction' => 'asc'],
        ];

        if (array_key_exists($sort, $allowedSorts)) {
            $sortConfig = $allowedSorts[$sort];
            if ($sort === 'eta_asc') {
                $query->orderByRaw('estimated_arrival IS NULL, estimated_arrival ASC');
            } elseif ($sort === 'eta_desc') {
                $query->orderByRaw('estimated_arrival IS NULL, estimated_arrival DESC');
            } else {
                $query->orderBy($sortConfig['column'], $sortConfig['direction']);
            }
        } else {
            $query->latest();
        }

        // Server-side pagination (default 10, min 1, max 50)
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage < 1) {
            $perPage = 10;
        } elseif ($perPage > 50) {
            $perPage = 50;
        }

        $shipments = $query->with(['customer', 'carrier', 'originLocation', 'destinationLocation', 'items'])
            ->paginate($perPage);

        return $this->successResponse(
            ShipmentResource::collection($shipments),
            'Daftar pengiriman berhasil diambil.',
            200,
            [
                'current_page' => $shipments->currentPage(),
                'per_page' => $shipments->perPage(),
                'total' => $shipments->total(),
                'last_page' => $shipments->lastPage(),
            ]
        );
    }

    /**
     * Buat shipment baru (khusus Admin & Operator).
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user->isCustomer()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        $validated = $request->validate([
            'customer_id' => ['required', Rule::exists('users', 'id')->where('role', 'customer')],
            'carrier_id' => ['required', 'exists:carriers,id'],
            'origin_location_id' => ['required', 'exists:locations,id', 'different:destination_location_id'],
            'destination_location_id' => ['required', 'exists:locations,id', 'different:origin_location_id'],
            'transport_mode' => ['required', 'in:OCEAN,AIR,LAND'],
            'cargo_type' => ['required', 'string', 'max:255'],
            'weight_kg' => ['required', 'numeric', 'min:0.01'],
            'expected_departure' => ['required', 'date'],
            'expected_arrival' => ['required', 'date', 'after:expected_departure'],
            'estimated_arrival' => ['nullable', 'date'],
            'tracking_number' => ['nullable', 'string', 'unique:shipments,tracking_number'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.weight_kg' => ['nullable', 'numeric', 'min:0'],
            'items.*.description' => ['nullable', 'string', 'max:500'],
        ]);

        $items = $validated['items'];
        $shipment = $this->shipmentService->createShipment($validated, $items, $user);

        return $this->successResponse(
            new ShipmentResource($shipment),
            'Pengiriman baru berhasil dibuat.',
            201
        );
    }

    /**
     * Detail rincian shipment.
     */
    public function show(Request $request, Shipment $shipment): JsonResponse
    {
        $user = $request->user();

        // Customer hanya boleh melihat miliknya
        if ($user->isCustomer() && $shipment->customer_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        $shipment->load([
            'customer',
            'carrier',
            'originLocation',
            'destinationLocation',
            'items',
            'trackingEvents.location',
            'exceptions.reporter',
            'exceptions.resolver',
        ]);

        $currentLocation = $this->trackingService->getCurrentLocation($shipment);
        $delayAnalysis = $this->etaService->evaluateDelay($shipment);

        $resource = (new ShipmentResource($shipment))->withCustomFields([
            'current_location' => $currentLocation,
            'delay_analysis' => $delayAnalysis,
        ]);

        return $this->successResponse(
            $resource,
            'Detail pengiriman berhasil diambil.'
        );
    }

    /**
     * Perbarui data atau transisi status shipment (Admin & Operator).
     */
    public function update(Request $request, Shipment $shipment): JsonResponse
    {
        $user = $request->user();
        if ($user->isCustomer()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        // Cek apakah request menyertakan transisi status
        if ($request->has('status') && $request->input('status') !== $shipment->status) {
            $request->validate([
                'status' => ['required', 'string', 'in:CREATED,BOOKED,PICKED_UP,IN_TRANSIT,DELIVERED,CANCELLED'],
                'note' => ['nullable', 'string', 'max:500'],
            ]);

            $shipment = $this->shipmentService->transitionStatus(
                $shipment,
                $request->input('status'),
                $user,
                $request->input('note')
            );
        }

        // Perbarui atribut informasi pengiriman jika ada
        $updateFields = $request->only([
            'customer_id',
            'carrier_id',
            'origin_location_id',
            'destination_location_id',
            'transport_mode',
            'cargo_type',
            'weight_kg',
            'expected_departure',
            'expected_arrival',
            'estimated_arrival',
        ]);

        if (! empty($updateFields) || $request->has('items')) {
            $validated = $request->validate([
                'customer_id' => ['sometimes', Rule::exists('users', 'id')->where('role', 'customer')],
                'carrier_id' => ['sometimes', 'exists:carriers,id'],
                'origin_location_id' => ['sometimes', 'exists:locations,id'],
                'destination_location_id' => ['sometimes', 'exists:locations,id'],
                'transport_mode' => ['sometimes', 'in:OCEAN,AIR,LAND'],
                'cargo_type' => ['sometimes', 'string', 'max:255'],
                'weight_kg' => ['sometimes', 'numeric', 'min:0.01'],
                'expected_departure' => ['sometimes', 'date'],
                'expected_arrival' => ['sometimes', 'date'],
                'estimated_arrival' => ['nullable', 'date'],
                'items' => ['nullable', 'array'],
                'items.*.item_name' => ['required_with:items', 'string', 'max:255'],
                'items.*.quantity' => ['required_with:items', 'integer', 'min:1'],
                'items.*.weight_kg' => ['nullable', 'numeric', 'min:0'],
                'items.*.description' => ['nullable', 'string', 'max:500'],
            ]);

            // Cek origin != destination jika keduanya dikirim
            $orig = $validated['origin_location_id'] ?? $shipment->origin_location_id;
            $dest = $validated['destination_location_id'] ?? $shipment->destination_location_id;
            if ($orig === $dest) {
                throw ValidationException::withMessages([
                    'destination_location_id' => 'Titik tujuan tidak boleh sama dengan titik asal.',
                ]);
            }

            try {
                $shipment = $this->shipmentService->updateShipment(
                    $shipment,
                    $validated,
                    $request->input('items', []),
                    $user
                );
                $this->etaService->syncShipmentDelay($shipment);
            } catch (\DomainException $e) {
                throw ValidationException::withMessages([
                    'status' => $e->getMessage(),
                ]);
            }
        }

        return $this->successResponse(
            new ShipmentResource($shipment->fresh(['customer', 'carrier', 'originLocation', 'destinationLocation', 'items'])),
            'Pengiriman berhasil diperbarui.'
        );
    }

    /**
     * Hapus shipment (khusus Admin).
     */
    public function destroy(Request $request, Shipment $shipment): JsonResponse
    {
        $user = $request->user();
        if (! $user->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        $this->shipmentService->deleteShipment($shipment, $user);

        return $this->successResponse(
            null,
            "Pengiriman '{$shipment->tracking_number}' berhasil dihapus."
        );
    }

    /**
     * Riwayat tracking checkpoint (Customer hanya miliknya).
     */
    public function tracking(Request $request, Shipment $shipment): JsonResponse
    {
        $user = $request->user();
        if ($user->isCustomer() && $shipment->customer_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        $events = $shipment->trackingEvents()
            ->with('location')
            ->orderBy('event_time', 'asc')
            ->get();

        $currentLocation = $this->trackingService->getCurrentLocation($shipment);

        return $this->successResponse([
            'current_location' => $currentLocation,
            'events' => TrackingEventResource::collection($events),
        ], 'Riwayat tracking berhasil diambil.');
    }

    /**
     * Tambah tracking checkpoint baru (Admin & Operator).
     */
    public function storeTracking(Request $request, Shipment $shipment): JsonResponse
    {
        $user = $request->user();
        if ($user->isCustomer()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:CREATED,BOOKED,PICKED_UP,IN_TRANSIT,DELIVERED,CANCELLED'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'event_time' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $event = $this->trackingService->addTrackingEvent($shipment, $validated, $user);

        return $this->successResponse(
            new TrackingEventResource($event->load('location')),
            'Tracking checkpoint berhasil ditambahkan.',
            201
        );
    }
}
