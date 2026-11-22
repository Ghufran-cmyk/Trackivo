<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the role-based dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $stats = [];
        $recentShipments = collect();
        $recentNotifications = collect();

        if ($user->isAdmin()) {
            $stats = [
                'total_shipments' => Shipment::count(),
                'active_shipments' => Shipment::whereIn('status', ['BOOKED', 'PICKED_UP', 'IN_TRANSIT'])->count(),
                'total_carriers' => Carrier::count(),
                'total_locations' => Location::count(),
                'total_users' => User::count(),
                'open_exceptions' => ShipmentException::whereIn('status', ['OPEN', 'INVESTIGATING'])->count(),
            ];

            $recentShipments = Shipment::with(['customer', 'carrier', 'originLocation', 'destinationLocation'])
                ->latest()
                ->take(5)
                ->get();

        } elseif ($user->isOperator()) {
            $stats = [
                'active_shipments' => Shipment::whereIn('status', ['BOOKED', 'PICKED_UP', 'IN_TRANSIT'])->count(),
                'in_transit' => Shipment::where('status', 'IN_TRANSIT')->count(),
                'delayed_shipments' => Shipment::where('delay_hours', '>', 0)->count(),
                'open_exceptions' => ShipmentException::whereIn('status', ['OPEN', 'INVESTIGATING'])->count(),
                'delivered_total' => Shipment::where('status', 'DELIVERED')->count(),
            ];

            $recentShipments = Shipment::with(['customer', 'carrier', 'originLocation', 'destinationLocation'])
                ->latest()
                ->take(5)
                ->get();

        } else { // Customer
            $stats = [
                'my_shipments' => Shipment::where('customer_id', $user->id)->count(),
                'in_transit' => Shipment::where('customer_id', $user->id)->where('status', 'IN_TRANSIT')->count(),
                'delivered' => Shipment::where('customer_id', $user->id)->where('status', 'DELIVERED')->count(),
                'unread_notifications' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
            ];

            $recentShipments = Shipment::where('customer_id', $user->id)
                ->with(['carrier', 'originLocation', 'destinationLocation'])
                ->latest()
                ->take(5)
                ->get();

            $recentNotifications = Notification::where('user_id', $user->id)
                ->latest()
                ->take(4)
                ->get();
        }

        return view('dashboard', compact('user', 'stats', 'recentShipments', 'recentNotifications'));
    }
}
