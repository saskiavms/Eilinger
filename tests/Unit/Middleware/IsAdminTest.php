<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\IsAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class IsAdminTest extends TestCase
{
    use RefreshDatabase;

    private IsAdmin $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new IsAdmin();
    }

    /** @test */
    public function admin_user_can_pass_through_middleware()
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        $this->actingAs($admin);

        $request = Request::create('/admin/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals('Success', $response->getContent());
    }

    /** @test */
    public function non_admin_user_is_redirected()
    {
        $user = User::factory()->create(['is_admin' => 0]);
        $this->actingAs($user);

        $request = Request::create('/admin/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/', $response->headers->get('Location'));
    }

    /** @test */
    public function unauthenticated_user_is_redirected()
    {
        $request = Request::create('/admin/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function admin_with_string_value_is_allowed()
    {
        $admin = User::factory()->create(['is_admin' => '1']);
        $this->actingAs($admin);

        $request = Request::create('/admin/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals('Success', $response->getContent());
    }

    /** @test */
    public function admin_with_boolean_true_is_allowed()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $request = Request::create('/admin/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals('Success', $response->getContent());
    }

    /** @test */
    public function admin_with_zero_value_is_rejected()
    {
        $user = User::factory()->create(['is_admin' => 0]);
        $this->actingAs($user);

        $request = Request::create('/admin/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function admin_with_false_value_is_rejected()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        $request = Request::create('/admin/dashboard');
        $next = function ($request) {
            return new Response('Success');
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(302, $response->getStatusCode());
    }
}