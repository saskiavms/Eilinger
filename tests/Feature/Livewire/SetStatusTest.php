<?php

namespace Tests\Feature\Livewire;

use App\Enums\ApplStatus;
use App\Livewire\SetStatus;
use App\Models\Application;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StatusUpdated;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class SetStatusTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    /** @test */
    public function component_renders_with_current_status()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $application = Application::factory()->create([
            'user_id' => $admin->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->assertSuccessful()
            ->assertSet('status', ApplStatus::PENDING->value);
    }

    /** @test */
    public function can_change_status_to_pending()
    {
        Notification::fake();

        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::NOTSEND,
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->set('status', ApplStatus::PENDING->value)
            ->call('setStatus')
            ->assertHasNoErrors();

        $this->assertEquals(ApplStatus::PENDING, $application->fresh()->appl_status);
    }

    /** @test */
    public function blocked_status_requires_rejection_reason()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->set('status', ApplStatus::BLOCKED->value)
            ->set('reason_rejected', '')
            ->call('setStatus')
            ->assertHasErrors(['reason_rejected']);
    }

    /** @test */
    public function blocked_status_saves_rejection_reason()
    {
        Notification::fake();

        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->set('status', ApplStatus::BLOCKED->value)
            ->set('reason_rejected', 'Antrag unvollständig')
            ->call('setStatus')
            ->assertHasNoErrors();

        $fresh = $application->fresh();
        $this->assertEquals(ApplStatus::BLOCKED, $fresh->appl_status);
        $this->assertEquals('Antrag unvollständig', $fresh->reason_rejected);
    }

    /** @test */
    public function approved_status_requires_approval_date()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::COMPLETE,
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->set('status', ApplStatus::APPROVED->value)
            ->set('approval_appl', '')
            ->call('setStatus')
            ->assertHasErrors(['approval_appl']);
    }

    /** @test */
    public function approved_status_saves_approval_date()
    {
        Notification::fake();

        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::COMPLETE,
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->set('status', ApplStatus::APPROVED->value)
            ->set('approval_appl', '2025-01-15')
            ->call('setStatus')
            ->assertHasNoErrors();

        $fresh = $application->fresh();
        $this->assertEquals(ApplStatus::APPROVED, $fresh->appl_status);
        $this->assertEquals('2025-01-15', $fresh->approval_appl->format('Y-m-d'));
    }

    /** @test */
    public function rejection_reason_is_cleared_when_status_is_not_blocked()
    {
        Notification::fake();

        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::BLOCKED,
            'reason_rejected' => 'Alter Grund',
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->set('status', ApplStatus::PENDING->value)
            ->call('setStatus')
            ->assertHasNoErrors();

        $this->assertNull($application->fresh()->reason_rejected);
    }

    /** @test */
    public function status_change_sends_notification_to_user()
    {
        Notification::fake();

        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->set('status', ApplStatus::WAITING->value)
            ->call('setStatus');

        Notification::assertSentTo($user, StatusUpdated::class);
    }

    /** @test */
    public function invalid_status_value_fails_validation()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->set('status', 'invalid-status')
            ->call('setStatus')
            ->assertHasErrors(['status']);
    }

    /** @test */
    public function can_add_payment_to_application()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->set('new_payment_amount', 500.00)
            ->set('new_payment_date', '2025-03-01')
            ->set('new_payment_notes', 'Erste Rate')
            ->call('addPayment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('payments', [
            'application_id' => $application->id,
            'amount' => 500.00,
            'notes' => 'Erste Rate',
        ]);
    }

    /** @test */
    public function add_payment_requires_amount_and_date()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->call('addPayment')
            ->assertHasErrors(['new_payment_amount', 'new_payment_date']);
    }

    /** @test */
    public function can_delete_payment_from_application()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
        ]);
        $payment = Payment::create([
            'application_id' => $application->id,
            'amount' => 300.00,
            'payment_date' => '2025-02-01',
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->call('deletePayment', $payment->id);

        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }

    /** @test */
    public function cannot_delete_payment_belonging_to_other_application()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
        ]);
        $otherApplication = Application::factory()->create(['user_id' => $user->id]);
        $payment = Payment::create([
            'application_id' => $otherApplication->id,
            'amount' => 300.00,
            'payment_date' => '2025-02-01',
        ]);

        Livewire::test(SetStatus::class, ['application' => $application])
            ->call('deletePayment', $payment->id);

        $this->assertDatabaseHas('payments', ['id' => $payment->id]);
    }
}
