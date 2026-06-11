<?php

namespace Tests\Feature;

use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_password_security_page(): void
    {
        $this->get(route('profile.security.edit'))
            ->assertRedirect(route('login'));
    }

    public function test_current_password_is_required_before_sending_a_code(): void
    {
        $user = User::factory()->create(['password' => 'old-password-123']);

        $this->actingAs($user)
            ->postJson(route('profile.security.send'), [
                'current_password' => 'incorrect-password',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('current_password');

        $this->assertDatabaseCount('email_verification_codes', 0);
    }

    public function test_verified_token_changes_password_and_can_only_be_used_once(): void
    {
        $user = User::factory()->create(['password' => 'old-password-123']);
        $token = Str::random(64);

        $verification = EmailVerificationCode::create([
            'email' => strtolower($user->email),
            'code_hash' => Hash::make('123456'),
            'purpose' => 'password_change',
            'verification_token_hash' => hash('sha256', $token),
            'attempts' => 0,
            'verified_at' => now(),
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->actingAs($user)
            ->put(route('profile.security.update'), [
                'verification_token' => $token,
                'password' => 'new-password-456',
                'password_confirmation' => 'new-password-456',
            ])
            ->assertRedirect(route('profile.edit'));

        $this->assertTrue(Hash::check('new-password-456', $user->fresh()->password));
        $this->assertNotNull($verification->fresh()->used_at);

        $this->actingAs($user)
            ->put(route('profile.security.update'), [
                'verification_token' => $token,
                'password' => 'another-password-789',
                'password_confirmation' => 'another-password-789',
            ])
            ->assertSessionHasErrors('verification_token');

        $this->assertTrue(Hash::check('new-password-456', $user->fresh()->password));
    }
}
