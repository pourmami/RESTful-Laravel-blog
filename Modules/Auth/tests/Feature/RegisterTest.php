<?php

namespace Modules\Auth\Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function test_user_can_receive_activation_code()
    {
        $email = 'testuser@example.com';

        $response = $this->postJson('/api/auth/send-code', [
            'email' => $email,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'کد فعال‌سازی ارسال شد.']);

        $this->assertDatabaseHas('activation_codes', [
            'email' => $email,
            'type' => 'register',
        ]);
    }

    /** @test */
    public function test_user_can_verify_valid_activation_code()
    {
        $email = Str::random(10) . '@example.com';
        $code = '112233';

        DB::table('activation_codes')->insert([
            'email' => $email,
            'code' => $code,
            'type' => 'register',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/auth/verify-code', [
            'email' => $email,
            'code' => $code,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function test_verification_fails_with_invalid_code()
    {
        $email = 'fail@example.com';

        DB::table('activation_codes')->insert([
            'email' => $email,
            'code' => '999999',
            'type' => 'register',
            'expires_at' => now()->subMinutes(1), // Expired
        ]);

        $response = $this->postJson('/api/auth/verify-code', [
            'email' => $email,
            'code' => '999999',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_complete_registration_after_verifying_code()
    {
        $user = User::factory()->create([
            'first_name' => null,
            'last_name' => null,
            'password' => null,
        ]);

        // ساخت یک توکن sanctum برای شبیه‌سازی مرحله verify
        $token = $user->createToken('temp-token', ['complete-register'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/complete-register', [
                'first_name' => 'علی',
                'last_name' => 'رضایی',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);

        $user->refresh();

        $this->assertEquals('علی', $user->first_name);
        $this->assertEquals('رضایی', $user->last_name);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_complete_register_requires_valid_token()
    {
        $response = $this->postJson('/api/auth/complete-register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(401); // Unauthorized
    }
}
