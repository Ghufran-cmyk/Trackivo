<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends ApiController
{
    /**
     * Registrasi pengguna baru melalui API (role terkunci sebagai customer).
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        // Role selalu dikunci sebagai 'customer' di server (mengabaikan input role dari client)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'company_name' => $validated['company_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Registrasi berhasil.', 201);
    }

    /**
     * Login via API dan dapatkan Sanctum Bearer token.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return $this->errorResponse('Kredensial yang diberikan tidak cocok dengan data kami.', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login berhasil.');
    }

    /**
     * Logout via API dan hapus current access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout berhasil.');
    }

    /**
     * Profil user terautentikasi via API.
     */
    public function user(Request $request): JsonResponse
    {
        return $this->successResponse(
            new UserResource($request->user()),
            'Data profil user berhasil diambil.'
        );
    }
}
