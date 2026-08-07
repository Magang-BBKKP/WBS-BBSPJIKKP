<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuplicateUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'sama@example.com']);

        $response = $this->post('/register', [
            'name' => 'User Baru',
            'email' => 'sama@example.com',
            'phone_number' => '08123456789',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_register_rejects_duplicate_phone_number(): void
    {
        User::factory()->create(['phone_number' => '08123456789']);

        $response = $this->post('/register', [
            'name' => 'User Baru',
            'email' => 'baru@example.com',
            'phone_number' => '08123456789',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors('phone_number');
        $this->assertGuest();
    }

    public function test_register_rejects_duplicate_phone_number_with_different_format(): void
    {
        User::factory()->create(['phone_number' => '08123456789']);

        $response = $this->post('/register', [
            'name' => 'User Baru',
            'email' => 'baru@example.com',
            'phone_number' => '628123456789',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors('phone_number');
        $this->assertGuest();
    }

    public function test_complete_phone_rejects_duplicate_phone_number(): void
    {
        $other = User::factory()->create(['phone_number' => '08123456789']);
        $current = User::factory()->create(['phone_number' => null]);

        $response = $this->actingAs($current)
            ->post('/complete-phone', [
                'phone_number' => $other->phone_number,
            ]);

        $response->assertSessionHasErrors('phone_number');
        $this->assertDatabaseHas('users', ['id' => $current->id, 'phone_number' => null]);
    }

    public function test_complete_phone_allows_same_phone_for_same_user(): void
    {
        $current = User::factory()->create(['phone_number' => '08123456789']);

        $response = $this->actingAs($current)
            ->post('/complete-phone', [
                'phone_number' => '08123456789',
            ]);

        $response->assertSessionDoesntHaveErrors();
    }

    public function test_profile_update_rejects_duplicate_phone_number(): void
    {
        $other = User::factory()->create(['phone_number' => '08123456789']);
        $current = User::factory()->create(['phone_number' => '08987654321']);

        $response = $this->actingAs($current)
            ->patch(route('profile.update'), [
                'name' => $current->name,
                'phone_number' => '08123456789',
            ]);

        $response->assertSessionHasErrors('phone_number');
        $this->assertDatabaseHas('users', ['id' => $current->id, 'phone_number' => '08987654321']);
    }
}
