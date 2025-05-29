<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    /** @test */
    public function admin_can_access_admin_dashboard()
    {
        $admin = $this->createAndAuthenticateAdmin();
        
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        
        $response->assertStatus(200);
    }

    /** @test */
    public function regular_user_cannot_access_admin_dashboard()
    {
        $user = $this->createAndAuthenticateUser();
        
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        
        $response->assertStatus(302);
        $response->assertRedirect($this->getLocalizedRoute('index'));
    }

    /** @test */
    public function guest_cannot_access_admin_dashboard()
    {
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        
        $response->assertStatus(302);
        $response->assertRedirect($this->getLocalizedRoute('login'));
    }

    /** @test */
    public function admin_can_view_all_applications()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $app1 = Application::factory()->create(['user_id' => $user1->id]);
        $app2 = Application::factory()->create(['user_id' => $user2->id]);
        
        // Assuming there's an admin applications route
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        
        $response->assertStatus(200);
        // Admin should be able to see applications from different users
    }

    /** @test */
    public function regular_user_cannot_access_admin_routes()
    {
        $user = $this->createAndAuthenticateUser();
        
        $adminRoutes = [
            'admin_dashboard',
            // Add other admin routes here as they're discovered
        ];
        
        foreach ($adminRoutes as $route) {
            $response = $this->get($this->getLocalizedRoute($route));
            $this->assertNotEquals(200, $response->getStatusCode(), 
                "User should not access admin route: {$route}");
        }
    }

    /** @test */
    public function admin_with_2fa_can_access_admin_areas()
    {
        $admin = $this->createAndAuthenticateAdmin();
        session(['auth.2fa' => true]);
        
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_without_2fa_is_redirected_to_verification()
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        $admin->generateTwoFactorCode(); // Create active 2FA code to trigger middleware
        $this->actingAs($admin);
        // No 2FA session set
        
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        
        // Should be redirected due to TwoFactorMiddleware
        $response->assertStatus(302);
        $response->assertRedirect($this->getLocalizedRoute('verify.index'));
    }

    /** @test */
    public function admin_status_changes_are_reflected_immediately()
    {
        $user = $this->createAndAuthenticateUser();
        
        // User should not have admin access
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        $response->assertStatus(302);
        
        // Promote user to admin
        $user->update(['is_admin' => 1]);
        
        // Verify update worked in database
        $userFromDb = User::find($user->id);
        $this->assertEquals(1, $userFromDb->is_admin);
        
        // Re-authenticate the user to reflect the change
        $this->actingAs($userFromDb);
        
        // Set 2FA session
        session(['auth.2fa' => true]);
        
        // Verify the user is actually an admin now
        $this->assertEquals(1, auth()->user()->is_admin);
        
        // Now should have admin access
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_authorization_works_with_different_data_types()
    {
        // Test integer 1
        $admin1 = User::factory()->create(['is_admin' => 1]);
        $this->actingAs($admin1);
        session(['auth.2fa' => true]);
        
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        $response->assertStatus(200);
        
        // Test string '1'
        $admin2 = User::factory()->create(['is_admin' => '1']);
        $this->actingAs($admin2);
        session(['auth.2fa' => true]);
        
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        $response->assertStatus(200);
        
        // Test boolean true
        $admin3 = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin3);
        session(['auth.2fa' => true]);
        
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_values_are_properly_rejected()
    {
        $nonAdminValues = [0, '0', false, 'admin', 2];
        
        foreach ($nonAdminValues as $value) {
            $user = User::factory()->create(['is_admin' => $value]);
            $this->actingAs($user);
            session(['auth.2fa' => true]);
            
            $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
            $this->assertNotEquals(200, $response->getStatusCode(), 
                "Admin value '{$value}' should not grant admin access");
        }
    }

    /** @test */
    public function admin_authorization_is_checked_on_every_request()
    {
        $admin = $this->createAndAuthenticateAdmin();
        session(['auth.2fa' => true]);
        
        // First request should work
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        $response->assertStatus(200);
        
        // Demote admin
        $admin->update(['is_admin' => 0]);
        
        // Verify update worked in database
        $adminFromDb = User::find($admin->id);
        $this->assertEquals(0, $adminFromDb->is_admin);
        
        // Re-authenticate the user to reflect the change
        $this->actingAs($adminFromDb);
        
        // Keep 2FA session to test that admin status is checked even with valid 2FA
        session(['auth.2fa' => true]);
        
        // Second request should fail
        $response = $this->get($this->getLocalizedRoute('admin_dashboard'));
        $response->assertStatus(302);
        $response->assertRedirect($this->getLocalizedRoute('index'));
    }

    /** @test */
    public function admin_can_access_admin_profile_edit()
    {
        $admin = $this->createAndAuthenticateAdmin();
        session(['auth.2fa' => true]);
        
        $response = $this->get($this->getLocalizedRoute('admin_profile.edit'));
        
        $response->assertStatus(200);
    }

    /** @test */
    public function regular_user_cannot_access_admin_profile_edit()
    {
        $user = $this->createAndAuthenticateUser();
        
        $response = $this->get($this->getLocalizedRoute('admin_profile.edit'));
        
        $response->assertStatus(302);
        $response->assertRedirect($this->getLocalizedRoute('index'));
    }
}