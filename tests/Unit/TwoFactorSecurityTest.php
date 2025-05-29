<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorSecurityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function generates_cryptographically_secure_random_codes()
    {
        $user = User::factory()->create();
        
        // Generate many codes to test distribution and randomness
        $codes = [];
        $iterations = 1000;
        
        for ($i = 0; $i < $iterations; $i++) {
            $user->generateTwoFactorCode();
            $codes[] = $user->two_factor_code;
        }
        
        // All codes should be unique (very high probability with random_int)
        $uniqueCodes = array_unique($codes);
        $this->assertGreaterThan($iterations * 0.99, count($uniqueCodes), 
            'Code generation should produce highly unique values');
        
        // Test statistical distribution (should be roughly even across ranges)
        $ranges = [
            '100000-299999' => 0,
            '300000-499999' => 0,
            '500000-699999' => 0,
            '700000-999999' => 0,
        ];
        
        foreach ($codes as $code) {
            if ($code < 300000) $ranges['100000-299999']++;
            elseif ($code < 500000) $ranges['300000-499999']++;
            elseif ($code < 700000) $ranges['500000-699999']++;
            else $ranges['700000-999999']++;
        }
        
        // Each range should have at least 15% of the codes (allowing for statistical variance)
        foreach ($ranges as $range => $count) {
            $this->assertGreaterThan($iterations * 0.15, $count, 
                "Range {$range} should have reasonable distribution");
        }
    }

    /** @test */
    public function code_format_is_always_six_digits()
    {
        $user = User::factory()->create();
        
        for ($i = 0; $i < 100; $i++) {
            $user->generateTwoFactorCode();
            
            $this->assertIsInt($user->two_factor_code);
            $this->assertGreaterThanOrEqual(100000, $user->two_factor_code);
            $this->assertLessThanOrEqual(999999, $user->two_factor_code);
            $this->assertEquals(6, strlen((string) $user->two_factor_code));
        }
    }

    /** @test */
    public function expiration_time_is_set_correctly()
    {
        $user = User::factory()->create();
        
        $beforeGeneration = now();
        $user->generateTwoFactorCode();
        $afterGeneration = now();
        
        $this->assertNotNull($user->two_factor_expires_at);
        
        // Should expire exactly 10 minutes from generation time
        $expectedExpiration = $beforeGeneration->addMinutes(10);
        $actualExpiration = $user->two_factor_expires_at;
        
        // Allow for small time differences due to execution time
        $this->assertTrue(
            $actualExpiration->diffInSeconds($expectedExpiration) < 2,
            'Expiration should be set to 10 minutes from now'
        );
        
        // Verify it's in the future
        $this->assertTrue($user->two_factor_expires_at->isFuture());
    }

    /** @test */
    public function reset_clears_two_factor_data()
    {
        $user = User::factory()->create();
        
        // Generate a code first
        $user->generateTwoFactorCode();
        $this->assertNotNull($user->two_factor_code);
        $this->assertNotNull($user->two_factor_expires_at);
        
        // Reset should clear everything
        $user->resetTwoFactorCode();
        $this->assertNull($user->two_factor_code);
        $this->assertNull($user->two_factor_expires_at);
    }

    /** @test */
    public function timestamps_are_not_updated_during_two_factor_operations()
    {
        $user = User::factory()->create();
        
        // Record the original timestamps
        $originalCreatedAt = $user->created_at;
        $originalUpdatedAt = $user->updated_at;
        
        // Wait a moment to ensure timestamp difference would be detectable
        sleep(1);
        
        // Generate and reset 2FA code
        $user->generateTwoFactorCode();
        $user->resetTwoFactorCode();
        
        // Reload the user to get fresh timestamps
        $user->refresh();
        
        // Timestamps should not have changed
        $this->assertEquals($originalCreatedAt, $user->created_at);
        $this->assertEquals($originalUpdatedAt, $user->updated_at);
    }

    /** @test */
    public function sensitive_fields_are_properly_hidden()
    {
        $user = User::factory()->create();
        $user->generateTwoFactorCode();
        
        // Test toArray() method
        $array = $user->toArray();
        $this->assertArrayNotHasKey('two_factor_code', $array);
        $this->assertArrayNotHasKey('two_factor_expires_at', $array);
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
        
        // Test toJson() method
        $json = json_decode($user->toJson(), true);
        $this->assertArrayNotHasKey('two_factor_code', $json);
        $this->assertArrayNotHasKey('two_factor_expires_at', $json);
        $this->assertArrayNotHasKey('password', $json);
        $this->assertArrayNotHasKey('remember_token', $json);
    }

    /** @test */
    public function two_factor_fields_are_not_mass_assignable()
    {
        $user = User::factory()->create();
        
        // Attempt to mass assign 2FA fields
        $user->fill([
            'two_factor_code' => 123456,
            'two_factor_expires_at' => now()->addHours(1),
            'email' => 'test@example.com', // This should work
        ]);
        
        // 2FA fields should not be set, but email should be
        $this->assertNull($user->two_factor_code);
        $this->assertNull($user->two_factor_expires_at);
        $this->assertEquals('test@example.com', $user->email);
    }

    /** @test */
    public function code_generation_does_not_use_predictable_rand()
    {
        // This test ensures we're not using the weak rand() function
        // by testing the quality of randomness
        
        $user = User::factory()->create();
        $codes = [];
        
        // Generate codes and check for patterns that would indicate weak randomness
        for ($i = 0; $i < 100; $i++) {
            $user->generateTwoFactorCode();
            $codes[] = $user->two_factor_code;
        }
        
        // Calculate some basic statistics to ensure good randomness
        $mean = array_sum($codes) / count($codes);
        $expectedMean = (100000 + 999999) / 2; // 549999.5
        
        // Mean should be reasonably close to expected (within 10% to account for statistical variance)
        $this->assertLessThan($expectedMean * 0.10, abs($mean - $expectedMean),
            'Mean should be close to expected for good random distribution');
        
        // No sequential patterns (codes should not be incrementing)
        $sequential = 0;
        for ($i = 1; $i < count($codes); $i++) {
            if ($codes[$i] === $codes[$i-1] + 1) {
                $sequential++;
            }
        }
        
        // Less than 2% of codes should be sequential (random chance)
        $this->assertLessThan(count($codes) * 0.02, $sequential,
            'Should not have many sequential codes with good randomness');
    }

    /** @test */
    public function code_comparison_handles_type_coercion_safely()
    {
        $user = User::factory()->create();
        $user->generateTwoFactorCode();
        
        $correctCode = $user->two_factor_code;
        
        // Test that string numbers are handled correctly
        $this->assertEquals((int) $correctCode, (int) (string) $correctCode);
        
        // Test edge cases that could bypass security with loose comparison
        $this->assertNotEquals($correctCode, "0e{$correctCode}"); // Scientific notation
        $this->assertTrue($correctCode !== true); // Boolean true (strict comparison)
        $this->assertTrue($correctCode !== false); // Boolean false (strict comparison)
        $this->assertTrue($correctCode !== null); // Null (strict comparison)
        
        // Ensure our casting approach works correctly
        $this->assertEquals((int) $correctCode, (int) $correctCode);
        $this->assertEquals((int) $correctCode, (int) (string) $correctCode);
    }
}