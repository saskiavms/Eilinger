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
        
        // Set application ID in session for the component
        session(['appl_id' => $this->application->id]);
    }

    /** @test */
    public function shows_incomplete_status_when_required_forms_are_incomplete()
    {
        Livewire::test(SendingForm::class, ['application' => $this->application])
            ->assertSet('userNoDraft', false)
            ->assertSet('addressNoDraft', false)
            ->assertSet('educationNoDraft', false)
            ->assertSeeHtml('text-red-600'); // Should show red X marks for incomplete items
    }

    /** @test */
    public function component_initializes_correctly()
    {
        // Test that the component can be rendered and initializes properly
        $component = Livewire::test(SendingForm::class, ['application' => $this->application]);
        
        $component->assertSet('userNoDraft', false)
                  ->assertSet('addressNoDraft', false)
                  ->assertSet('educationNoDraft', false)
                  ->assertSet('costNoDraft', false)
                  ->assertSet('financingNoDraft', false)
                  ->assertSet('enclosureNoDraft', false);
        
        // Test that calling completeApplication works without errors
        $component->call('completeApplication');
        
        // Test that the component renders successfully
        $component->assertSee('Finaler Check'); // From the German translation
    }
}
