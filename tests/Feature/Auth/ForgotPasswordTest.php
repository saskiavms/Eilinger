<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function passwordRequestRoute(): string
    {
        return route('password.request', app()->getLocale());
    }

    protected function passwordEmailGetRoute(): string
    {
        return route('password.request', app()->getLocale());
    }

    protected function passwordEmailPostRoute(): string
    {
        return route('password.email', app()->getLocale());
    }

    public function testUserCanViewAnEmailPasswordForm()
    {
        $response = $this->get($this->passwordRequestRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.forgot-password');
    }

    public function testUserCanNotViewAnEmailPasswordFormWhenAuthenticated()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->get($this->passwordRequestRoute());

        $response->assertFound();
    }

    public function testUserReceivesAnEmailWithAPasswordResetLink()
    {
        Notification::fake();
        $user = User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $response = $this->post($this->passwordEmailPostRoute(), [
            'email' => 'john@example.com',
        ]);

        $this->assertNotNull($token = DB::table('password_reset_tokens')->first());
        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    public function testUserDoesNotReceiveEmailWhenNotRegistered()
    {
        Notification::fake();

        $response = $this->from($this->passwordEmailGetRoute())->post($this->passwordEmailPostRoute(), [
            'email' => 'nobody@example.com',
        ]);

        $response->assertRedirect($this->passwordEmailGetRoute());
        $response->assertSessionHasErrors('email');
        Notification::assertNotSentTo(User::factory()->make(['email' => 'nobody@example.com']), ResetPassword::class);
    }

    public function testEmailIsRequired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $response = $this->post($this->passwordEmailPostRoute(), []);
    }

    public function testEmailIsAValidEmail()
    {
        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $response = $this->post($this->passwordEmailPostRoute(), [
            'email' => 'not-an-email'
        ]);
    }
}
