<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Tampilkan daftar seluruh pengguna beserta role, perusahaan, dan kontak.
     */
    public function index(): View
    {
        $users = User::withCount('shipments')
            ->orderBy('name')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Form tambah user baru oleh Administrator.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Simpan user baru ke database.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_USER',
            'target_type' => User::class,
            'target_id' => $user->id,
            'old_values' => null,
            'new_values' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'company_name' => $user->company_name,
                'phone' => $user->phone,
            ],
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')
            ->with('success', "Pengguna '{$user->name}' ({$user->role}) berhasil ditambahkan.");
    }

    /**
     * Form edit user.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Perbarui data user dan role di database.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'company_name' => $user->company_name,
            'phone' => $user->phone,
        ];

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_USER',
            'target_type' => User::class,
            'target_id' => $user->id,
            'old_values' => $oldValues,
            'new_values' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'company_name' => $user->company_name,
                'phone' => $user->phone,
            ],
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')
            ->with('success', "Data pengguna '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Hapus user (cegah admin menghapus dirinya sendiri).
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        }

        if ($user->shipments()->exists()) {
            return back()->with('error', "Pengguna '{$user->name}' tidak dapat dihapus karena memiliki {$user->shipments()->count()} shipment terkait.");
        }

        $userName = $user->name;
        $userId = $user->id;
        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $user->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_USER',
            'target_type' => User::class,
            'target_id' => $userId,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('users.index')
            ->with('success', "Pengguna '{$userName}' berhasil dihapus.");
    }
}
