<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\TwoFactorMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class TwoFactorMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private TwoFactorMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new TwoFactorMiddleware();
    }

    /** @test */
    public function authenticated_user_with_active_2fa_code_is_redirected_to_verify()
    {
        $user = User::factory()->create();
        $user->generateTwoFactorCode(); // This sets two_factor_code and two_factor_expires_at
        $this->actingAs($user);

        $request = Request::create('/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        // Should redirect to 2FA verification
        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function user_without_2fa_code_can_proceed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        // No 2FA code set, so middleware should allow through

        $request = Request::create('/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals('Success', $response->getContent());
    }

    /** @test */
    public function guest_user_can_proceed()
    {
        // Guest users are not affected by this middleware
        $request = Request::create('/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals('Success', $response->getContent());
    }

    /** @test */
    public function user_with_expired_2fa_code_is_logged_out()
    {
        $user = User::factory()->create();
        $user->generateTwoFactorCode();
        // Manually expire the code
        $user->two_factor_expires_at = now()->subMinutes(1);
        $user->save();
        $this->actingAs($user);

        $request = Request::create('/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertGuest();
    }

    /** @test */
    public function user_on_verify_route_with_2fa_code_can_proceed()
    {
        $user = User::factory()->create();
        $user->generateTwoFactorCode();
        $this->actingAs($user);

        // Create a request to the verify route
        $request = Request::create('/verify');
        $request->setRouteResolver(function () use ($request) {
            $route = new \Illuminate\Routing\Route(['GET'], '/verify', []);
            $route->name('verify.index');
            return $route;
        });
        
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals('Success', $response->getContent());
    }

    /** @test */
    public function middleware_handles_2fa_code_expiration()
    {
        $user = User::factory()->create();
        $user->generateTwoFactorCode();
        $this->actingAs($user);

        // Test that non-expired codes allow redirect
        $request = Request::create('/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        // Should redirect to verify since 2FA code exists and is not expired
        $this->assertEquals(302, $response->getStatusCode());
    }
}