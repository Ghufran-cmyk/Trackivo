<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * a) Guest dapat mengakses GET /forgot-password (status 200).
     */
    public function test_guest_can_view_forgot_password_page(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Lupa Kata Sandi?');
        $response->assertSee('Kirim Tautan Reset');
    }

    /**
     * b) Guest dapat meminta tautan reset untuk email user yang terdaftar dan notifikasi ResetPassword terkirim.
     */
    public function test_guest_can_request_password_reset_link_and_notification_is_sent(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'pengguna@trackivo.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'pengguna@trackivo.com',
        ]);

        $response->assertSessionHas('status', 'Jika alamat email terdaftar di Trackivo, tautan atur ulang kata sandi telah dikirimkan.');
        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * c) Guest dapat membuka GET /reset-password/{token} (status 200).
     */
    public function test_guest_can_view_reset_password_page_with_token(): void
    {
        $user = User::factory()->create([
            'email' => 'reset_user@trackivo.com',
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Atur Ulang Kata Sandi');
        $response->assertSee($token);
        $response->assertSee($user->email);
    }

    /**
     * d) Guest dapat mereset kata sandi menggunakan token yang valid, password di database ter-hash dengan nilai baru, dan user bisa login memakai password baru tersebut.
     */
    public function test_user_can_reset_password_with_valid_token_and_login_with_new_password(): void
    {
        $user = User::factory()->create([
            'email' => 'gantisandi@trackivo.com',
            'password' => Hash::make('password_lama_123'),
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'gantisandi@trackivo.com',
            'password' => 'password_baru_456',
            'password_confirmation' => 'password_baru_456',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Kata sandi berhasil diperbarui. Silakan masuk dengan kata sandi baru Anda.');

        // Verifikasi password baru ter-hash di database
        $user->refresh();
        $this->assertTrue(Hash::check('password_baru_456', $user->password));
        $this->assertFalse(Hash::check('password_lama_123', $user->password));

        // Verifikasi login menggunakan password baru berhasil
        $loginResponse = $this->post('/login', [
            'email' => 'gantisandi@trackivo.com',
            'password' => 'password_baru_456',
        ]);

        $loginResponse->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * e) Percobaan reset menggunakan token tidak valid ditolak dan password lama tidak berubah.
     */
    public function test_user_cannot_reset_password_with_invalid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'aman@trackivo.com',
            'password' => Hash::make('password_asli_tetap'),
        ]);

        $response = $this->from(route('password.reset', ['token' => 'token_palsu']))
            ->post(route('password.update'), [
                'token' => 'token_palsu_tidak_valid',
                'email' => 'aman@trackivo.com',
                'password' => 'sandi_baru_gagal',
                'password_confirmation' => 'sandi_baru_gagal',
            ]);

        $response->assertRedirect(route('password.reset', ['token' => 'token_palsu']));
        $response->assertSessionHasErrors('email');

        // Pastikan password lama tetap tidak berubah
        $user->refresh();
        $this->assertTrue(Hash::check('password_asli_tetap', $user->password));
        $this->assertFalse(Hash::check('sandi_baru_gagal', $user->password));
        $this->assertGuest();
    }

    /**
     * Validasi gagal jika konfirmasi kata sandi tidak cocok.
     */
    public function test_password_reset_fails_if_passwords_do_not_match(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password_baru_1',
            'password_confirmation' => 'password_beda_2',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Validasi gagal jika kata sandi kurang dari 8 karakter.
     */
    public function test_password_reset_fails_if_password_too_short(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'pendek',
            'password_confirmation' => 'pendek',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
