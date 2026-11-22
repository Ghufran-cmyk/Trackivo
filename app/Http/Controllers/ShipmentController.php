<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportExceptionRequest;
use App\Http\Requests\StoreShipmentRequest;
use App\Http\Requests\StoreTrackingEventRequest;
use App\Http\Requests\UpdateShipmentRequest;
use App\Http\Requests\UpdateShipmentStatusRequest;
use App\Models\AuditLog;
use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use App\Services\EtaService;
use App\Services\ExceptionService;
use App\Services\ShipmentService;
use App\Services\TrackingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    public function __construct(
        protected ShipmentService $shipmentService,
        protected EtaService $etaService,
        protected TrackingService $trackingService,
        protected ExceptionService $exceptionService
    ) {}

    /**
     * Tampilkan daftar pengiriman dengan kontrol akses peran dan server-side filtering.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

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

        // Filter origin
        if ($request->filled('origin_location_id')) {
            $query->where('origin_location_id', $request->origin_location_id);
        }

        // Filter destination
        if ($request->filled('destination_location_id')) {
            $query->where('destination_location_id', $request->destination_location_id);
        }

        $shipments = $query->with([
            'customer',
            'carrier',
            'originLocation',
            'destinationLocation',
            'items',
        ])
        ->latest()
        ->paginate(10)
        ->withQueryString();

        // Data pendukung untuk form filter
        $carriers = Carrier::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('shipments.index', compact('shipments', 'carriers', 'locations'));
    }

    /**
     * Form tambah shipment baru (Admin & Operator).
     */
    public function create(): View
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $carriers = Carrier::where('is_active', true)->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('shipments.create', compact('customers', 'carriers', 'locations'));
    }

    /**
     * Simpan shipment baru beserta items melalui ShipmentService.
     */
    public function store(StoreShipmentRequest $request): RedirectResponse
    {
        $shipment = $this->shipmentService->createShipment(
            $request->validated(),
            $request->input('items', []),
            auth()->user()
        );

        // Sinkronkan evaluasi delay awal
        $this->etaService->syncShipmentDelay($shipment);

        return redirect()->route('shipments.show', $shipment)
            ->with('success', "Pengiriman {$shipment->tracking_number} berhasil dibuat.");
    }

    /**
     * Tampilkan detail lengkap shipment beserta tracking events, panel status, lokasi terkini, dan analisis delay.
     */
    public function show(Shipment $shipment): View
    {
        // Otorisasi ketat untuk Customer (hanya milik sendiri, selain itu abort 403)
        if (auth()->user()->isCustomer() && $shipment->customer_id !== auth()->id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat pengiriman ini.');
        }

        $shipment->load([
            'customer',
            'carrier',
            'originLocation',
            'destinationLocation',
            'creator',
            'items',
            'trackingEvents' => fn ($q) => $q->orderBy('event_time', 'asc'),
            'trackingEvents.location',
            'trackingEvents.creator',
            'exceptions' => fn ($q) => $q->latest('detected_at'),
            'exceptions.reporter',
            'exceptions.resolver',
        ]);

        $allowedNextStatuses = $this->shipmentService->getAllowedNextStatuses($shipment->status);
        $currentLocation = $this->trackingService->getCurrentLocation($shipment);
        $delayAnalysis = $this->etaService->evaluateDelay($shipment);
        $locations = Location::orderBy('name')->get();

        return view('shipments.show', compact('shipment', 'allowedNextStatuses', 'currentLocation', 'delayAnalysis', 'locations'));
    }

    /**
     * Form edit shipment (Admin & Operator).
     */
    public function edit(Shipment $shipment): View|RedirectResponse
    {
        if (in_array($shipment->status, ['DELIVERED', 'CANCELLED'], true)) {
            return redirect()->route('shipments.show', $shipment)
                ->with('error', "Pengiriman dengan status '{$shipment->status}' sudah final dan tidak dapat diubah.");
        }

        $shipment->load('items');
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $carriers = Carrier::where('is_active', true)->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('shipments.edit', compact('shipment', 'customers', 'carriers', 'locations'));
    }

    /**
     * Perbarui data shipment melalui ShipmentService.
     */
    public function update(UpdateShipmentRequest $request, Shipment $shipment): RedirectResponse
    {
        try {
            $this->shipmentService->updateShipment(
                $shipment,
                $request->validated(),
                $request->input('items', []),
                auth()->user()
            );

            // Sinkronkan kalkulasi keterlambatan
            $this->etaService->syncShipmentDelay($shipment);

            return redirect()->route('shipments.show', $shipment)
                ->with('success', "Pengiriman {$shipment->tracking_number} berhasil diperbarui.");
        } catch (\DomainException $e) {
            return redirect()->route('shipments.show', $shipment)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Transisi status shipment melalui State Machine di ShipmentService.
     */
    public function updateStatus(UpdateShipmentStatusRequest $request, Shipment $shipment): RedirectResponse
    {
        $shipment = $this->shipmentService->transitionStatus(
            $shipment,
            $request->validated('status'),
            auth()->user(),
            $request->input('note')
        );

        return redirect()->route('shipments.show', $shipment)
            ->with('success', "Status pengiriman berhasil diperbarui menjadi {$shipment->status}.");
    }

    /**
     * Tambah tracking checkpoint event baru (Admin & Operator).
     */
    public function storeTracking(StoreTrackingEventRequest $request, Shipment $shipment): RedirectResponse
    {
        try {
            $this->trackingService->addTrackingEvent(
                $shipment,
                $request->validated(),
                auth()->user()
            );

            return redirect()->route('shipments.show', $shipment)
                ->with('success', "Checkpoint tracking baru berhasil dicatat pada riwayat pengiriman.");
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    /**
     * Laporkan kendala / exception baru pada shipment (Admin & Operator).
     */
    public function storeException(ReportExceptionRequest $request, Shipment $shipment): RedirectResponse
    {
        try {
            $exception = $this->exceptionService->reportException(
                $shipment,
                $request->validated(),
                auth()->user()
            );

            return redirect()->route('shipments.show', $shipment)
                ->with('success', "Kendala operasional {$exception->type} ({$exception->severity}) berhasil dilaporkan.");
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    /**
     * Hapus shipment (Hanya Admin dan hanya untuk status CREATED atau CANCELLED).
     */
    public function destroy(Shipment $shipment): RedirectResponse
    {
        if (! in_array($shipment->status, ['CREATED', 'CANCELLED'], true)) {
            return back()->with('error', "Pengiriman berstatus '{$shipment->status}' tidak dapat dihapus demi keamanan audit operasional. Hanya status CREATED atau CANCELLED yang dapat dihapus.");
        }

        $oldValues = $shipment->toArray();
        $trackingNumber = $shipment->tracking_number;
        $shipmentId = $shipment->id;

        $shipment->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_SHIPMENT',
            'target_type' => Shipment::class,
            'target_id' => $shipmentId,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('shipments.index')
            ->with('success', "Pengiriman '{$trackingNumber}' berhasil dihapus dari sistem.");
    }
}
