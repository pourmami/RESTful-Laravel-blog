<?php

namespace Modules\Auth\Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $password = 'password123';

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['email', 'first_name', 'last_name']
            ]);
    }

    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        $email = Str::random(5) . '@example.com';
        User::factory()->create([
            'email' => $email,
            'password' => bcrypt('correct_password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    /** @test */
    public function user_cannot_login_with_invalid_email()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'notexist@example.com',
            'password' => 'any_password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }
}
