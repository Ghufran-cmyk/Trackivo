<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * Web login valid mengautentikasi dan mengarahkan ke dashboard.
     */
    public function test_web_login_valid_authenticates_and_redirects(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@trackivo.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Web login invalid ditolak dan tetap berstatus guest dengan error session.
     */
    public function test_web_login_invalid_fails(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@trackivo.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Akses halaman terproteksi tanpa login dialihkan (redirect) ke /login.
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));

        $responseShipments = $this->get('/shipments');
        $responseShipments->assertRedirect(route('login'));
    }

    /**
     * Public registration web selalu membuat role customer meskipun client mencoba menyisipkan role=admin.
     */
    public function test_web_registration_strictly_locks_role_as_customer(): void
    {
        $response = $this->post('/register', [
            'name' => 'Hacker Wannabe',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // Percobaan manipulasi input role
            'company_name' => 'Evil Corp',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'hacker@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role);
        $this->assertNotEquals('admin', $user->role);
    }

    /**
     * API register berhasil membuat akun baru terkunci role customer dan mengembalikan Sanctum token.
     */
    public function test_api_register_locks_role_as_customer_and_returns_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'API Customer Test',
            'email' => 'api_customer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // Percobaan manipulasi role via API
            'company_name' => 'PT Mitra Global',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Registrasi berhasil.',
            ])
            ->assertJsonPath('data.user.role', 'customer')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'role', 'company_name'],
                    'token',
                    'token_type',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'api_customer@example.com',
            'role' => 'customer',
        ]);
    }

    /**
     * API login valid mengembalikan status 200 dan Sanctum Bearer token.
     */
    public function test_api_login_valid_returns_token(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@trackivo.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login berhasil.',
            ])
            ->assertJsonPath('data.user.email', 'admin@trackivo.com')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['user', 'token', 'token_type'],
            ]);
    }

    /**
     * API login invalid ditolak dengan 401 dan pesan error bersih.
     */
    public function test_api_login_invalid_credentials_returns_401(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@trackivo.com',
            'password' => 'salah-password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
            ]);
    }

    /**
     * GET /api/user dengan Bearer token mengembalikan profil pengguna terautentikasi.
     */
    public function test_api_get_authenticated_user_profile(): void
    {
        $user = User::where('email', 'admin@trackivo.com')->first();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Data profil user berhasil diambil.',
                'data' => [
                    'id' => $user->id,
                    'email' => 'admin@trackivo.com',
                    'role' => 'admin',
                ],
            ]);
    }

    /**
     * POST /api/logout menghapus token aktif saat ini.
     */
    public function test_api_logout_revokes_current_token(): void
    {
        $user = User::where('email', 'admin@trackivo.com')->first();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logout berhasil.',
            ]);

        $this->assertCount(0, $user->fresh()->tokens);
    }
}
