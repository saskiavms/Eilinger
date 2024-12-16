<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Livewire\Antrag\SendingForm;
use App\Models\Application;
use App\Enums\ApplStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class SendingFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    private Application $application;

    public function setUp(): void
    {
        parent::setUp();
        $this->createAndAuthenticateUser();

        $this->application = Application::factory()->create([
            'user_id' => $this->authUser->id,
            'appl_status' => ApplStatus::NOTSEND
        ]);
    }

    /** @test */
    public function submit_button_should_be_disabled_when_required_forms_are_incomplete()
    {
        Livewire::test(SendingForm::class, ['application' => $this->application])
            ->assertSet('completeApp', false)
            ->assertSeeHtml('disabled class="px-4 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed"');
    }

    /** @test */
    public function submit_button_should_be_enabled_when_all_required_forms_are_complete()
    {
        // Setup complete application data
        $this->application->update([
            'user_draft' => false,
            'address_draft' => false,
            'education_draft' => false,
            'account_draft' => false,
            'cost_draft' => false,
            'financing_draft' => false,
            'enclosure_draft' => false
        ]);

        Livewire::test(SendingForm::class, ['application' => $this->application])
            ->call('completeApplication')
            ->assertSet('completeApp', true)
            ->assertDontSeeHtml('disabled class="px-4 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed"')
            ->assertSeeHtml('class="px-4 py-2 bg-danger hover:bg-danger-hover text-white rounded-md transition-colors"');
    }
}
