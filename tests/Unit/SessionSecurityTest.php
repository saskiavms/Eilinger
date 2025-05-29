<?php

namespace Tests\Unit;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class SessionSecurityTest extends TestCase
{
    /** @test */
    public function session_encryption_is_enabled()
    {
        $this->assertTrue(config('session.encrypt'), 
            'Session encryption should be enabled for security');
    }

    /** @test */
    public function session_cookies_are_secure_by_default()
    {
        // Test that secure cookies are enabled by default (for production)
        $defaultSecure = config('session.secure');
        $this->assertTrue($defaultSecure, 
            'Session cookies should be secure by default');
    }

    /** @test */
    public function session_cookies_are_http_only()
    {
        $this->assertTrue(config('session.http_only'), 
            'Session cookies should be HTTP only to prevent XSS attacks');
    }

    /** @test */
    public function session_same_site_is_strict()
    {
        $this->assertEquals('strict', config('session.same_site'), 
            'Session same-site should be strict for CSRF protection');
    }

    /** @test */
    public function session_lifetime_is_reasonable()
    {
        $lifetime = config('session.lifetime');
        
        // Session lifetime should be between 30 minutes and 8 hours
        $this->assertGreaterThanOrEqual(30, $lifetime, 
            'Session lifetime should be at least 30 minutes');
        $this->assertLessThanOrEqual(480, $lifetime, 
            'Session lifetime should not exceed 8 hours for security');
    }

    /** @test */
    public function session_cookie_name_is_not_default()
    {
        $cookieName = config('session.cookie');
        
        // Should not be the default Laravel session cookie name
        $this->assertNotEquals('laravel_session', $cookieName, 
            'Session cookie name should be customized');
        
        // Should contain app name or be customized
        $this->assertNotEmpty($cookieName, 
            'Session cookie name should not be empty');
    }

    /** @test */
    public function session_driver_is_secure()
    {
        $driver = config('session.driver');
        
        // File and database drivers are secure, avoid cookie driver for sensitive data
        // Array driver is acceptable for testing
        $secureDrivers = ['file', 'database', 'redis', 'memcached', 'array'];
        $this->assertContains($driver, $secureDrivers, 
            'Session driver should be secure (not cookie-based for sensitive data)');
        
        // In non-testing environments, should not be array
        if (!app()->environment('testing')) {
            $this->assertNotEquals('array', $driver,
                'Session driver should not be array in production');
        }
    }

    /** @test */
    public function session_encryption_key_is_set()
    {
        $appKey = config('app.key');
        
        $this->assertNotEmpty($appKey, 
            'Application key must be set for session encryption');
        
        // Should start with base64: for Laravel
        $this->assertStringStartsWith('base64:', $appKey, 
            'Application key should be base64 encoded');
    }

    /** @test */
    public function encrypted_session_data_is_not_readable()
    {
        // Test that encrypted session data cannot be easily read
        $testData = 'sensitive_session_data_123';
        
        // Encrypt the data as Laravel would for sessions
        $encrypted = Crypt::encrypt($testData);
        
        // Encrypted data should not contain the original data
        $this->assertStringNotContainsString($testData, $encrypted, 
            'Encrypted session data should not contain plaintext');
        
        // Should be significantly different in length
        $this->assertGreaterThan(strlen($testData) * 2, strlen($encrypted), 
            'Encrypted data should be significantly longer than plaintext');
        
        // Should be able to decrypt back to original
        $decrypted = Crypt::decrypt($encrypted);
        $this->assertEquals($testData, $decrypted, 
            'Encrypted data should decrypt to original value');
    }

    /** @test */
    public function session_id_regeneration_works()
    {
        // Start session
        session_start();
        $originalId = session_id();
        
        // Regenerate ID
        session_regenerate_id(true);
        $newId = session_id();
        
        // IDs should be different
        $this->assertNotEquals($originalId, $newId, 
            'Session ID should change after regeneration');
        
        // Both should be valid session ID format
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $originalId, 
            'Original session ID should be alphanumeric');
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $newId, 
            'New session ID should be alphanumeric');
        
        // Should be reasonable length (typically 32-40 characters)
        $this->assertGreaterThanOrEqual(26, strlen($newId), 
            'Session ID should be sufficiently long');
    }

    /** @test */
    public function session_garbage_collection_is_configured()
    {
        $lottery = config('session.lottery');
        
        $this->assertIsArray($lottery, 
            'Session lottery should be an array');
        $this->assertCount(2, $lottery, 
            'Session lottery should have exactly 2 elements');
        
        list($numerator, $denominator) = $lottery;
        
        $this->assertGreaterThan(0, $numerator, 
            'Session garbage collection numerator should be positive');
        $this->assertGreaterThan($numerator, $denominator, 
            'Session garbage collection denominator should be greater than numerator');
        
        // Probability should be reasonable (not too frequent, not too rare)
        $probability = $numerator / $denominator;
        $this->assertGreaterThan(0.001, $probability, 
            'Garbage collection should happen often enough');
        $this->assertLessThan(0.5, $probability, 
            'Garbage collection should not happen too frequently');
    }

    /** @test */
    public function session_configuration_is_production_ready()
    {
        // Check multiple security settings at once
        $config = config('session');
        
        $securityChecks = [
            'encrypt' => true,
            'http_only' => true,
            'secure' => true, // Should be true in production
            'same_site' => 'strict',
        ];
        
        foreach ($securityChecks as $setting => $expectedValue) {
            $this->assertEquals($expectedValue, $config[$setting], 
                "Session {$setting} should be {$expectedValue} for production security");
        }
    }

    /** @test */
    public function session_files_location_is_secure()
    {
        $filesPath = config('session.files');
        
        // Should be in storage directory (not public)
        $this->assertStringContainsString('storage', $filesPath, 
            'Session files should be stored in storage directory');
        
        // Should not be in public directory
        $this->assertStringNotContainsString('public', $filesPath, 
            'Session files should not be in public directory');
        
        // Path should exist or be creatable
        if (!file_exists($filesPath)) {
            $this->assertTrue(is_writable(dirname($filesPath)), 
                'Session files directory should be writable');
        } else {
            $this->assertTrue(is_writable($filesPath), 
                'Session files directory should be writable');
        }
    }
}