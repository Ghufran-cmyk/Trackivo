<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ShipmentExceptionResource;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Services\ExceptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExceptionController extends ApiController
{
    public function __construct(
        protected ExceptionService $exceptionService
    ) {}

    /**
     * Daftar kendala operasional pada suatu pengiriman (Customer terbatas miliknya).
     */
    public function index(Request $request, Shipment $shipment): JsonResponse
    {
        $user = $request->user();
        if ($user->isCustomer() && $shipment->customer_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        $exceptions = $shipment->exceptions()
            ->with(['reporter', 'resolver'])
            ->latest()
            ->get();

        return $this->successResponse(
            ShipmentExceptionResource::collection($exceptions),
            'Daftar kendala pengiriman berhasil diambil.'
        );
    }

    /**
     * Laporkan kendala baru pada shipment (Admin & Operator).
     */
    public function store(Request $request, Shipment $shipment): JsonResponse
    {
        $user = $request->user();
        if ($user->isCustomer()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:' . implode(',', ExceptionService::VALID_TYPES)],
            'severity' => ['required', 'string', 'in:' . implode(',', ExceptionService::VALID_SEVERITIES)],
            'delay_impact_hours' => ['nullable', 'integer', 'min:0'],
            'description' => ['required', 'string', 'max:1000'],
            'detected_at' => ['nullable', 'date'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $exception = $this->exceptionService->reportException($shipment, $validated, $user);

        return $this->successResponse(
            new ShipmentExceptionResource($exception),
            'Kendala operasional berhasil dilaporkan.',
            201
        );
    }

    /**
     * Perbarui status kendala (Admin & Operator).
     */
    public function update(Request $request, ShipmentException $exception): JsonResponse
    {
        $user = $request->user();
        if ($user->isCustomer()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:INVESTIGATING,RESOLVED'],
            'resolution_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $updated = $this->exceptionService->updateException($exception, $validated, $user);

        return $this->successResponse(
            new ShipmentExceptionResource($updated),
            'Status kendala operasional berhasil diperbarui.'
        );
    }
}
