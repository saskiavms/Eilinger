<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Currency;
use App\Models\User;
use App\Enums\ApplStatus;
use App\Enums\Bereich;
use App\Enums\Form;
use App\Livewire\User\Antrag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SubmissionDateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CurrencySeeder::class);
    }

    public function test_submission_date_is_set_when_application_is_submitted()
    {
        $user = User::factory()->create();
        $currency = Currency::where('abbreviation', 'CHF')->first();
        
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => ApplStatus::NOTSEND,
            'submission_date' => null,
        ]);

        $this->assertNull($application->submission_date);

        $this->actingAs($user);

        Livewire::test(Antrag::class, ['application_id' => $application->id])
            ->call('sendApplication');

        $application->refresh();

        $this->assertEquals(ApplStatus::PENDING, $application->appl_status);
        $this->assertNotNull($application->submission_date);
        $this->assertInstanceOf(\Carbon\Carbon::class, $application->submission_date);
        $this->assertEquals(now()->format('Y-m-d H:i'), $application->submission_date->format('Y-m-d H:i'));
    }

    public function test_submission_date_is_displayed_in_pdf_report()
    {
        $this->markTestSkipped('PDF report requires full application data setup including address, education, etc.');
        
        // This test would require setting up all the related models that the PDF expects
        // In actual report generation, this is handled by ReportGenerator::generateReport()
    }

    public function test_submission_date_is_displayed_in_admin_view()
    {
        $user = User::factory()->create();
        $currency = Currency::where('abbreviation', 'CHF')->first();
        
        $submissionDate = now()->subDays(3);
        
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => ApplStatus::PENDING,
            'submission_date' => $submissionDate,
            'start_appl' => now(),
        ]);

        $html = view('partials.accAppl', compact('application'))->render();

        $this->assertStringContainsString('Eingereicht am', $html);
        $this->assertStringContainsString($submissionDate->format('d.m.Y H:i'), $html);
    }

    public function test_submission_date_is_fillable_and_castable()
    {
        $user = User::factory()->create();
        $currency = Currency::where('abbreviation', 'CHF')->first();
        
        $application = new Application([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'name' => 'Test Application',
            'appl_status' => ApplStatus::NOTSEND,
            'bereich' => Bereich::Menschen,
            'form' => Form::Stipendium,
            'start_appl' => now(),
            'submission_date' => now(),
        ]);

        $this->assertTrue(in_array('submission_date', $application->getFillable()));
        $this->assertArrayHasKey('submission_date', $application->getCasts());
        $this->assertEquals('datetime', $application->getCasts()['submission_date']);
    }

    public function test_submission_date_remains_null_for_unsent_applications()
    {
        $user = User::factory()->create();
        $currency = Currency::where('abbreviation', 'CHF')->first();
        
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => ApplStatus::NOTSEND,
            'submission_date' => null,
        ]);

        $this->assertNull($application->submission_date);
        
        // Update other fields without submitting
        $application->update(['name' => 'Updated Name']);
        
        $application->refresh();
        $this->assertNull($application->submission_date);
    }
}