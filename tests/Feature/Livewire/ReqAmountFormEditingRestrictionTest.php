<?php

namespace Tests\Feature\Livewire;

use App\Enums\ApplStatus;
use App\Livewire\Antrag\ReqAmountForm;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class ReqAmountFormEditingRestrictionTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    /** @test */
    public function user_can_edit_req_amount_form_when_application_is_not_approved()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        session(['appl_id' => $application->id]);

        $component = Livewire::test(ReqAmountForm::class);

        $component->set('req_amount', 5000)
            ->set('payout_plan', 'Monatlich');

        $component->call('saveReqAmount');

        $component->assertHasNoErrors();

        // Check if application was actually updated
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'req_amount' => 5000,
        ]);
    }

    /** @test */
    public function user_cannot_edit_req_amount_form_when_application_is_approved()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
            'req_amount' => 3000, // Original amount
        ]);

        session(['appl_id' => $application->id]);

        $component = Livewire::test(ReqAmountForm::class);

        $component->set('req_amount', 5000)
            ->set('payout_plan', 'Monatlich');

        $component->call('saveReqAmount');

        // Check that application was not updated due to editing restriction
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'req_amount' => 3000, // Should remain original amount
        ]);

        $this->assertDatabaseMissing('applications', [
            'id' => $application->id,
            'req_amount' => 5000, // Should not be updated
        ]);
    }

    /** @test */
    public function req_amount_form_shows_editable_state_correctly()
    {
        $user = $this->createAndAuthenticateUser();

        // Test editable application
        $editableApplication = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        session(['appl_id' => $editableApplication->id]);

        $editableComponent = Livewire::test(ReqAmountForm::class);
        $this->assertTrue($editableComponent->get('isEditable'));

        // Test non-editable application
        $nonEditableApplication = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
        ]);

        session(['appl_id' => $nonEditableApplication->id]);

        $nonEditableComponent = Livewire::test(ReqAmountForm::class);
        $this->assertFalse($nonEditableComponent->get('isEditable'));
    }
}
