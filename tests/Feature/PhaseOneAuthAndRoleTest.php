<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PhaseOneAuthAndRoleTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;
    public function test_guest_is_redirected_to_login_from_root(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Trackivo');
        $response->assertSee('admin@trackivo.com');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::where('email', 'admin@trackivo.com')->first();
        $this->assertNotNull($user);

        $response = $this->post('/login', [
            'email' => 'admin@trackivo.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@trackivo.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Customer');
        $response->assertDontSee('admin'); // Never show role selection
    }

    public function test_new_user_registration_strictly_locks_role_as_customer(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Customer',
            'email' => 'newcustomer@test.com',
            'company_name' => 'PT Global Test Express',
            'phone' => '+62 812-9999-8888',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // Malicious attempt to claim admin role
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));

        $newUser = User::where('email', 'newcustomer@test.com')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('customer', $newUser->role); // MUST be customer
        $this->assertTrue($newUser->isCustomer());
        $this->assertFalse($newUser->isAdmin());

        // Clean up
        $newUser->delete();
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'customer1@trackivo.com')->first();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_renders_for_admin_with_admin_menus(): void
    {
        $admin = User::where('email', 'admin@trackivo.com')->first();
        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Portal Admin');
        $response->assertSee('Audit Logs');
        $response->assertSee('Settings');
        $response->assertSee('Locations');
    }

    public function test_dashboard_renders_for_operator_with_operator_menus(): void
    {
        $operator = User::where('email', 'operator@trackivo.com')->first();
        $response = $this->actingAs($operator)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Portal Operator');
        $response->assertSee('Exceptions');
    }

    public function test_dashboard_renders_for_customer_with_customer_menus(): void
    {
        $customer = User::where('email', 'customer1@trackivo.com')->first();
        $response = $this->actingAs($customer)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Portal Customer');
        $response->assertSee('My Shipments');
        $response->assertSee('Notifications');
    }

    public function test_role_middleware_blocks_unauthorized_access(): void
    {
        Route::get('/test-admin-only', function () {
            return 'admin-ok';
        })->middleware(['auth', 'role:admin']);

        $customer = User::where('email', 'customer1@trackivo.com')->first();

        $response = $this->actingAs($customer)->get('/test-admin-only');
        $response->assertStatus(403);

        $admin = User::where('email', 'admin@trackivo.com')->first();
        $responseAdmin = $this->actingAs($admin)->get('/test-admin-only');
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('admin-ok');
    }
}
