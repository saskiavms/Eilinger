<?php

namespace Tests\Feature\Livewire;

use App\Enums\ApplStatus;
use App\Livewire\Antrag\AccountForm;
use App\Models\Account;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class ApplicationEditingRestrictionTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    /** @test */
    public function user_can_edit_account_form_when_application_is_not_approved()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        session(['appl_id' => $application->id]);

        $component = Livewire::test(AccountForm::class);

        $component->set('name_bank', 'Test Bank')
            ->set('city_bank', 'Test City')
            ->set('owner', 'Test Owner')
            ->set('IBAN', 'CH93 0076 2011 6238 5295 7');

        $component->call('saveAccount');

        // Debug the errors and session
        $component->assertHasNoErrors();

        // Check if account was actually saved
        $this->assertDatabaseHas('accounts', [
            'application_id' => $application->id,
            'name_bank' => 'Test Bank',
        ]);
    }

    /** @test */
    public function user_cannot_edit_account_form_when_application_is_approved()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
        ]);

        session(['appl_id' => $application->id]);

        $component = Livewire::test(AccountForm::class);

        $component->set('name_bank', 'Test Bank')
            ->set('city_bank', 'Test City')
            ->set('owner', 'Test Owner')
            ->set('IBAN', 'CH93 0076 2011 6238 5295 7');

        $component->call('saveAccount');

        // Check that account was not saved due to editing restriction
        $this->assertDatabaseMissing('accounts', [
            'application_id' => $application->id,
            'name_bank' => 'Test Bank',
        ]);
    }

    /** @test */
    public function form_shows_editable_state_correctly()
    {
        $user = $this->createAndAuthenticateUser();

        // Test editable application
        $editableApplication = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        session(['appl_id' => $editableApplication->id]);

        $editableComponent = Livewire::test(AccountForm::class);
        $this->assertTrue($editableComponent->get('isEditable'));

        // Test non-editable application
        $nonEditableApplication = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
        ]);

        session(['appl_id' => $nonEditableApplication->id]);

        $nonEditableComponent = Livewire::test(AccountForm::class);
        $this->assertFalse($nonEditableComponent->get('isEditable'));
    }

    /** @test */
    public function form_handles_missing_application_gracefully()
    {
        $user = $this->createAndAuthenticateUser();

        // Set invalid application ID
        session(['appl_id' => 99999]);

        $component = Livewire::test(AccountForm::class);

        // Should default to editable when application not found
        $this->assertTrue($component->get('isEditable'));
    }
}
