<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\TwoFactorCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;
use Tests\Traits\LocalizedTestTrait;
use Tests\Traits\WithAuthUser;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase;
    use LocalizedTestTrait;
    use WithAuthUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Clear rate limiters before each test
        RateLimiter::clear('two-factor-attempts:1');
        RateLimiter::clear('two-factor-resend:1');
    }

    /** @test */
    public function user_model_generates_secure_two_factor_code()
    {
        $user = User::factory()->create();
        
        // Generate multiple codes to test randomness
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $user->generateTwoFactorCode();
            $codes[] = $user->two_factor_code;
            
            // Assert code is 6 digits
            $this->assertIsInt($user->two_factor_code);
            $this->assertGreaterThanOrEqual(100000, $user->two_factor_code);
            $this->assertLessThanOrEqual(999999, $user->two_factor_code);
            
            // Assert expiration is set to 10 minutes from now
            $this->assertNotNull($user->two_factor_expires_at);
            $this->assertTrue($user->two_factor_expires_at->diffInMinutes(now()) <= 10);
        }
        
        // Ensure codes are random (very unlikely to have duplicates in 10 generations)
        $this->assertEquals(count($codes), count(array_unique($codes)));
    }

    /** @test */
    public function two_factor_code_is_hidden_from_serialization()
    {
        $user = User::factory()->create();
        $user->generateTwoFactorCode();
        
        $serialized = $user->toArray();
        
        $this->assertArrayNotHasKey('two_factor_code', $serialized);
        $this->assertArrayNotHasKey('two_factor_expires_at', $serialized);
    }

    /** @test */
    public function two_factor_fields_are_not_mass_assignable()
    {
        $user = User::factory()->create();
        
        // Attempt mass assignment
        $user->fill([
            'two_factor_code' => 123456,
            'two_factor_expires_at' => now(),
        ]);
        
        // These fields should not be set
        $this->assertNull($user->two_factor_code);
        $this->assertNull($user->two_factor_expires_at);
    }

    /** @test */
    public function user_can_access_two_factor_page_with_valid_session()
    {
        // Create foundation data to avoid view errors
        \App\Models\Foundation::factory()->create();
        
        $user = $this->createAndAuthenticateUser();
        
        // Set 2FA session
        session(['auth.2fa' => true]);
        
        $response = $this->get($this->getLocalizedRoute('verify.index'));
        
        $response->assertSuccessful();
        $response->assertViewIs('auth.twoFactor');
    }

    /** @test */
    public function user_redirected_to_login_without_two_factor_session()
    {
        $user = $this->createAndAuthenticateUser();
        
        $response = $this->get($this->getLocalizedRoute('verify.index'));
        
        $response->assertRedirect($this->getLocalizedRoute('login'));
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function user_can_verify_correct_two_factor_code()
    {
        $user = $this->createAndAuthenticateUser();
        session(['auth.2fa' => true]);
        
        $user->generateTwoFactorCode();
        $correctCode = $user->two_factor_code;
        
        $response = $this->post($this->getLocalizedRoute('verify.store'), [
            'two_factor_code' => $correctCode,
        ]);
        
        $response->assertRedirect($this->getLocalizedRoute('user_dashboard'));
        
        // Ensure code is cleared after successful verification
        $user->refresh();
        $this->assertNull($user->two_factor_code);
        $this->assertNull($user->two_factor_expires_at);
    }

    /** @test */
    public function admin_redirected_to_admin_dashboard_after_verification()
    {
        $admin = $this->createAndAuthenticateAdmin();
        session(['auth.2fa' => true]);
        
        $admin->generateTwoFactorCode();
        $correctCode = $admin->two_factor_code;
        
        $response = $this->post($this->getLocalizedRoute('verify.store'), [
            'two_factor_code' => $correctCode,
        ]);
        
        $response->assertRedirect($this->getLocalizedRoute('admin_dashboard'));
    }

    /** @test */
    public function user_cannot_verify_incorrect_two_factor_code()
    {
        $user = $this->createAndAuthenticateUser();
        session(['auth.2fa' => true]);
        
        $user->generateTwoFactorCode();
        $incorrectCode = $user->two_factor_code + 1; // Definitely wrong
        
        $response = $this->post($this->getLocalizedRoute('verify.store'), [
            'two_factor_code' => $incorrectCode,
        ]);
        
        $response->assertSessionHasErrors(['two_factor_code']);
        
        // Ensure code is NOT cleared after failed verification
        $user->refresh();
        $this->assertNotNull($user->two_factor_code);
    }

    /** @test */
    public function user_cannot_verify_expired_two_factor_code()
    {
        $user = $this->createAndAuthenticateUser();
        session(['auth.2fa' => true]);
        
        $user->generateTwoFactorCode();
        $correctCode = $user->two_factor_code;
        
        // Manually expire the code
        $user->two_factor_expires_at = now()->subMinutes(1);
        $user->save();
        
        $response = $this->post($this->getLocalizedRoute('verify.store'), [
            'two_factor_code' => $correctCode,
        ]);
        
        $response->assertSessionHasErrors(['two_factor_code']);
        
        // Ensure code is cleared after expiration
        $user->refresh();
        $this->assertNull($user->two_factor_code);
        $this->assertNull($user->two_factor_expires_at);
    }

    /** @test */
    public function two_factor_verification_is_rate_limited()
    {
        $user = $this->createAndAuthenticateUser();
        session(['auth.2fa' => true]);
        
        $user->generateTwoFactorCode();
        $incorrectCode = 000000; // Wrong code
        
        // Make 5 failed attempts (the limit)
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post($this->getLocalizedRoute('verify.store'), [
                'two_factor_code' => $incorrectCode,
            ]);
            $response->assertSessionHasErrors(['two_factor_code']);
        }
        
        // 6th attempt should be rate limited
        $response = $this->post($this->getLocalizedRoute('verify.store'), [
            'two_factor_code' => $incorrectCode,
        ]);
        
        $response->assertSessionHasErrors(['two_factor_code']);
        $this->assertStringContainsString('Too many attempts', 
            $response->getSession()->get('errors')->first('two_factor_code'));
    }

    /** @test */
    public function rate_limit_is_cleared_on_successful_verification()
    {
        $user = $this->createAndAuthenticateUser();
        session(['auth.2fa' => true]);
        
        $user->generateTwoFactorCode();
        $correctCode = $user->two_factor_code;
        
        // Make some failed attempts first
        for ($i = 0; $i < 3; $i++) {
            $this->post($this->getLocalizedRoute('verify.store'), [
                'two_factor_code' => 000000,
            ]);
        }
        
        // Successful verification should clear rate limit
        $response = $this->post($this->getLocalizedRoute('verify.store'), [
            'two_factor_code' => $correctCode,
        ]);
        
        $response->assertRedirect($this->getLocalizedRoute('user_dashboard'));
        
        // Verify rate limit was cleared by checking we can make new attempts
        $key = 'two-factor-attempts:' . $user->id;
        $this->assertFalse(RateLimiter::tooManyAttempts($key, 5));
    }

    /** @test */
    public function user_can_resend_two_factor_code()
    {
        Notification::fake();
        
        $user = $this->createAndAuthenticateUser();
        
        $response = $this->get($this->getLocalizedRoute('verify.resend'));
        
        $response->assertRedirect();
        $response->assertSessionHas('message');
        
        // Verify notification was sent
        Notification::assertSentTo($user, TwoFactorCode::class);
        
        // Verify new code was generated
        $user->refresh();
        $this->assertNotNull($user->two_factor_code);
        $this->assertNotNull($user->two_factor_expires_at);
    }

    /** @test */
    public function two_factor_code_resend_is_rate_limited()
    {
        Notification::fake();
        
        $user = $this->createAndAuthenticateUser();
        
        // Make 3 resend requests (the limit)
        for ($i = 0; $i < 3; $i++) {
            $response = $this->get($this->getLocalizedRoute('verify.resend'));
            $response->assertRedirect();
        }
        
        // 4th attempt should be rate limited
        $response = $this->get($this->getLocalizedRoute('verify.resend'));
        
        $response->assertSessionHasErrors(['two_factor_code']);
        $this->assertStringContainsString('Too many resend requests', 
            $response->getSession()->get('errors')->first('two_factor_code'));
    }

    /** @test */
    public function session_is_regenerated_after_successful_verification()
    {
        $user = $this->createAndAuthenticateUser();
        session(['auth.2fa' => true]);
        
        $user->generateTwoFactorCode();
        $correctCode = $user->two_factor_code;
        
        // Store the original session ID
        $originalSessionId = session()->getId();
        
        $response = $this->post($this->getLocalizedRoute('verify.store'), [
            'two_factor_code' => $correctCode,
        ]);
        
        // Session ID should be regenerated
        $newSessionId = session()->getId();
        $this->assertNotEquals($originalSessionId, $newSessionId);
    }

    /** @test */
    public function two_factor_code_validation_requires_integer()
    {
        $user = $this->createAndAuthenticateUser();
        session(['auth.2fa' => true]);
        
        $response = $this->post($this->getLocalizedRoute('verify.store'), [
            'two_factor_code' => 'invalid',
        ]);
        
        $response->assertSessionHasErrors(['two_factor_code']);
    }

    /** @test */
    public function two_factor_code_validation_requires_field()
    {
        $user = $this->createAndAuthenticateUser();
        session(['auth.2fa' => true]);
        
        $response = $this->post($this->getLocalizedRoute('verify.store'), []);
        
        $response->assertSessionHasErrors(['two_factor_code']);
    }

    /** @test */
    public function ajax_request_returns_json_for_two_factor_status()
    {
        $user = $this->createAndAuthenticateUser();
        session(['auth.2fa' => true]);
        
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])->get($this->getLocalizedRoute('verify.index'));
        
        $response->assertJson(['valid' => true]);
    }

    /** @test */
    public function ajax_request_returns_false_without_two_factor_session()
    {
        $user = $this->createAndAuthenticateUser();
        
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])->get($this->getLocalizedRoute('verify.index'));
        
        $response->assertJson(['valid' => false]);
    }
}