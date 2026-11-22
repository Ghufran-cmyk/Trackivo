<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * Tampilkan dashboard analitik dengan metrik KPI, Chart.js, dan tabel data terbaru.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $dashboardData = $this->dashboardService->getDashboardData($user);

        return view('dashboard', [
            'user' => $user,
            'kpis' => $dashboardData['kpis'],
            'statusDistribution' => $dashboardData['statusDistribution'],
            'carrierDistribution' => $dashboardData['carrierDistribution'],
            'recentShipments' => $dashboardData['recentShipments'],
            'activeExceptions' => $dashboardData['activeExceptions'],
            'recentNotifications' => $dashboardData['recentNotifications'],
            'unreadNotificationsCount' => $dashboardData['unreadNotificationsCount'],
        ]);
    }
}
