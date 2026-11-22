<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Tampilkan daftar in-app notifications milik user yang sedang login dengan filter dan paginasi.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Notification::where('user_id', $user->id)
            ->with('shipment');

        // Filter status baca (unread / read / all)
        if ($request->filled('filter')) {
            if ($request->filter === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->filter === 'read') {
                $query->where('is_read', true);
            }
        }

        $notifications = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $totalCount = Notification::where('user_id', $user->id)->count();

        return view('notifications.index', compact('notifications', 'unreadCount', 'totalCount'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca (verifikasi kepemilikan ketat).
     */
    public function markAsRead(Request $request, Notification $notification): RedirectResponse
    {
        // Otorisasi: HANYA pemilik notifikasi yang berhak mengubah statusnya
        if ($notification->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah notifikasi ini.');
        }

        if (! $notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return back()->with('success', 'Notifikasi berhasil ditandai sebagai sudah dibaca.');
    }

    /**
     * Tandai seluruh notifikasi yang belum dibaca milik pengguna saat ini sebagai sudah dibaca.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $updated = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', "Sebanyak {$updated} notifikasi berhasil ditandai sudah dibaca.");
    }
}
