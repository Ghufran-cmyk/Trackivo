<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Tampilkan jejak rekam aktivitas dan audit sistem khusus Administrator.
     */
    public function index(Request $request): View
    {
        $query = AuditLog::with('user');

        // Pencarian bebas (action, target_type, target_id, ip_address)
        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('target_type', 'like', "%{$search}%")
                  ->orWhere('target_id', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Filter Aktor (user_id)
        if ($request->filled('user_id')) {
            if ($request->user_id === 'system') {
                $query->whereNull('user_id');
            } else {
                $query->where('user_id', $request->user_id);
            }
        }

        // Filter Jenis Aksi (action)
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter Entitas (target_type)
        if ($request->filled('target_type')) {
            $query->where('target_type', $request->target_type);
        }

        // Filter Rentang Tanggal (created_at)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()
            ->paginate(15)
            ->withQueryString();

        // Data pendukung untuk dropdown filter
        $users = User::orderBy('name')->get();
        $distinctActions = AuditLog::distinct()->pluck('action')->filter()->values();
        $distinctTargetTypes = AuditLog::distinct()->pluck('target_type')->filter()->values();

        return view('audit-logs.index', compact('logs', 'users', 'distinctActions', 'distinctTargetTypes'));
    }
}
