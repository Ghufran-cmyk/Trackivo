<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateExceptionRequest;
use App\Models\ShipmentException;
use App\Services\ExceptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ExceptionController extends Controller
{
    public function __construct(
        protected ExceptionService $exceptionService
    ) {}

    /**
     * Tampilkan daftar seluruh kendala pengiriman dengan filter dan paginasi.
     */
    public function index(Request $request): View
    {
        $query = ShipmentException::with([
            'shipment.customer',
            'shipment.carrier',
            'reporter',
            'resolver',
        ]);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        // Search tracking number or description
        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('resolution_note', 'like', "%{$search}%")
                  ->orWhereHas('shipment', function ($sq) use ($search) {
                      $sq->where('tracking_number', 'like', "%{$search}%");
                  });
            });
        }

        $exceptions = $query->latest('detected_at')
            ->paginate(10)
            ->withQueryString();

        $validTypes = ExceptionService::VALID_TYPES;
        $validSeverities = ExceptionService::VALID_SEVERITIES;
        $validStatuses = ExceptionService::VALID_STATUSES;

        return view('exceptions.index', compact('exceptions', 'validTypes', 'validSeverities', 'validStatuses'));
    }

    /**
     * Perbarui status kendala (investigasi atau penyelesaian/resolution).
     */
    public function update(UpdateExceptionRequest $request, ShipmentException $exception): RedirectResponse
    {
        try {
            $this->exceptionService->updateException(
                $exception,
                $request->validated(),
                auth()->user()
            );

            return redirect()->route('exceptions.index')
                ->with('success', "Status kendala #{$exception->id} pada pengiriman {$exception->shipment?->tracking_number} berhasil diperbarui.");
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }
}
