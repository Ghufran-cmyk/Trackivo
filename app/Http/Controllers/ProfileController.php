<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan formulir pengaturan profil akun.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Perbarui informasi profil (nama, email) dan foto profil pengguna.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('profile.edit')->with('profile_status', 'Informasi profil dan foto berhasil diperbarui.');
    }

    /**
     * Hapus foto profil pengguna dan kembalikan ke avatar inisial.
     */
    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar) {
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = null;
            $user->save();
        }

        return redirect()->route('profile.edit')->with('profile_status', 'Foto profil berhasil dihapus.');
    }

    /**
     * Perbarui kata sandi akun pengguna dengan verifikasi kata sandi saat ini.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('password_status', 'Kata sandi berhasil diperbarui.');
    }
}
