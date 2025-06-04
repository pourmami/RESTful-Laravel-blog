<?php

namespace Modules\Auth\Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ForgetPasswordTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function test_user_can_request_password_reset_code()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('activation_codes', [
            'email' => $user->email,
            'type' => 'reset',
        ]);
    }

    /** @test */
    public function test_user_can_reset_password_with_valid_code()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword')
        ]);

        DB::table('activation_codes')->insert([
            'email' => $user->email,
            'code' => '123456',
            'type' => 'reset',
            'expires_at' => now()->addMinutes(10)
        ]);

        $response = $this->postJson('/api/auth/reset-password', [
            'email' => $user->email,
            'code' => '123456',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200);
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }
}
