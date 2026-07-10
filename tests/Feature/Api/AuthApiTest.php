<?php

namespace Tests\Feature\Api;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_sign_up_and_receives_otp(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/sign-up', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'contact_no' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email', 'contact_no', 'picture'],
                    'otp',
                ],
                'message',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        Mail::assertSent(OtpMail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }

    public function test_signup_validation_fails_with_invalid_data(): void
    {
        $response = $this->postJson('/api/v1/sign-up', [
            'name' => 'T',
            'email' => 'invalid-email',
            'contact_no' => '',
            'password' => '123',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_verify_otp_and_get_token(): void
    {
        $user = User::factory()->unverified()->create([
            'otp' => '123456',
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/v1/verify-otp', [
            'email' => $user->email,
            'otp' => '123456',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email', 'contact_no', 'picture'],
                    'token',
                ],
                'message',
            ]);

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->otp);
        $this->assertNull($user->otp_expires_at);
    }

    public function test_otp_verification_fails_with_invalid_otp(): void
    {
        $user = User::factory()->unverified()->create([
            'otp' => '123456',
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/v1/verify-otp', [
            'email' => $user->email,
            'otp' => '999999',
        ]);

        $response->assertStatus(422);
    }

    public function test_otp_verification_fails_with_expired_otp(): void
    {
        $user = User::factory()->unverified()->create([
            'otp' => '123456',
            'otp_expires_at' => now()->subMinutes(1),
        ]);

        $response = $this->postJson('/api/v1/verify-otp', [
            'email' => $user->email,
            'otp' => '123456',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_sign_in(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/sign-in', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email', 'contact_no', 'picture'],
                    'token',
                ],
            ]);
    }

    public function test_sign_in_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/sign-in', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }
}
