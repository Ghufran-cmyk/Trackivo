<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper untuk membuat file gambar dummy yang kompatibel tanpa ekstensi GD.
     */
    protected function createFakeAvatar(string $filename = 'avatar.png'): UploadedFile
    {
        if (extension_loaded('gd')) {
            return UploadedFile::fake()->image($filename, 200, 200);
        }

        // Minimal valid 1x1 transparent PNG binary
        $pngContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

        return UploadedFile::fake()->createWithContent($filename, $pngContent);
    }

    /**
     * a) Guest mengakses GET /profile diarahkan ke /login.
     */
    public function test_guest_is_redirected_to_login_when_accessing_profile(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    /**
     * b) Ketiga role (Admin, Operator, dan Customer) masing-masing dapat mengakses GET /profile (status 200).
     */
    public function test_all_three_roles_can_access_profile_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $operator = User::factory()->create(['role' => 'operator']);
        $customer = User::factory()->create(['role' => 'customer']);

        // Admin
        $responseAdmin = $this->actingAs($admin)->get(route('profile.edit'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Pengaturan Akun');
        $responseAdmin->assertSee($admin->name);
        $responseAdmin->assertSee('Administrator');

        // Operator
        $responseOperator = $this->actingAs($operator)->get(route('profile.edit'));
        $responseOperator->assertStatus(200);
        $responseOperator->assertSee('Pengaturan Akun');
        $responseOperator->assertSee($operator->name);
        $responseOperator->assertSee('Logistics Operator');

        // Customer
        $responseCustomer = $this->actingAs($customer)->get(route('profile.edit'));
        $responseCustomer->assertStatus(200);
        $responseCustomer->assertSee('Pengaturan Akun');
        $responseCustomer->assertSee($customer->name);
        $responseCustomer->assertSee('Customer');
    }

    /**
     * c) User dapat mengganti nama, email, dan mengunggah foto profil (PP) valid melalui PATCH /profile;
     *    file tersimpan di disk public, kolom avatar terisi, dan percobaan menyisipkan role => 'admin'
     *    dari akun customer TIDAK mengubah role user.
     */
    public function test_user_can_update_profile_and_avatar_without_modifying_role(): void
    {
        Storage::fake('public');

        $customer = User::factory()->create([
            'name' => 'Budi Customer',
            'email' => 'budi@customer.com',
            'role' => 'customer',
        ]);

        $avatarFile = $this->createFakeAvatar('my_photo.png');

        $response = $this->actingAs($customer)->patch(route('profile.update'), [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@customer.com',
            'avatar' => $avatarFile,
            'role' => 'admin', // Percobaan eskalasi privilege
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('profile_status', 'Informasi profil dan foto berhasil diperbarui.');

        $customer->refresh();

        $this->assertEquals('Budi Santoso', $customer->name);
        $this->assertEquals('budi.santoso@customer.com', $customer->email);
        $this->assertEquals('customer', $customer->role, 'Role pengguna TIDAK boleh berubah melalui pembaruan profil!');
        $this->assertNotNull($customer->avatar);

        Storage::disk('public')->assertExists($customer->avatar);
        $this->assertNotNull($customer->avatarUrl());
        $this->assertStringContainsString('storage/' . $customer->avatar, $customer->avatarUrl());
    }

    /**
     * d) Mengunggah file non-gambar (mis. .pdf atau .txt) ditolak dengan error validasi pada field avatar.
     */
    public function test_uploading_non_image_file_is_rejected_with_validation_error(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $nonImageFile = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $nonImageFile,
        ]);

        $response->assertSessionHasErrors('avatar');
        $user->refresh();
        $this->assertNull($user->avatar);
    }

    /**
     * e) User dapat menghapus foto profil melalui DELETE /profile/avatar sehingga file
     *    terhapus dari storage dan kolom avatar kembali null.
     */
    public function test_user_can_delete_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'avatar' => 'avatars/sample.png',
        ]);

        // Siapkan file di disk fake
        Storage::disk('public')->put('avatars/sample.png', 'fake image content');
        Storage::disk('public')->assertExists('avatars/sample.png');

        $response = $this->actingAs($user)->delete(route('profile.avatar.destroy'));

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('profile_status', 'Foto profil berhasil dihapus.');

        $user->refresh();

        $this->assertNull($user->avatar);
        $this->assertNull($user->avatarUrl());
        Storage::disk('public')->assertMissing('avatars/sample.png');
    }

    /**
     * f) User dapat memperbarui kata sandi melalui PUT /profile/password jika current_password benar,
     *    dan ditolak jika current_password salah.
     */
    public function test_user_can_update_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'old-password-123',
            'password' => 'new-secure-password-456',
            'password_confirmation' => 'new-secure-password-456',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('password_status', 'Kata sandi berhasil diperbarui.');

        $user->refresh();
        $this->assertTrue(Hash::check('new-secure-password-456', $user->password));
    }

    /**
     * f.2) Percobaan memperbarui kata sandi ditolak jika current_password salah.
     */
    public function test_password_update_fails_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'wrong-current-password',
            'password' => 'new-secure-password-456',
            'password_confirmation' => 'new-secure-password-456',
        ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'current_password');

        $user->refresh();
        $this->assertTrue(Hash::check('old-password-123', $user->password));
    }

    /**
     * Test validasi konfirmasi kata sandi baru tidak cocok.
     */
    public function test_password_update_fails_when_confirmation_does_not_match(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'old-password-123',
            'password' => 'new-secure-password-456',
            'password_confirmation' => 'mismatched-password',
        ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'password');

        $user->refresh();
        $this->assertTrue(Hash::check('old-password-123', $user->password));
    }
}
