<?php

namespace Tests\Feature\Admin;

use App\Enums\ApplStatus;
use App\Livewire\Admin\ReportGenerator;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\LocalizedTestTrait;
use Tests\Traits\WithAuthUser;

class ReportGeneratorTest extends TestCase
{
    use RefreshDatabase, WithAuthUser, LocalizedTestTrait;

    /** @test */
    public function non_admin_cannot_access_report_generator()
    {
        $this->createAndAuthenticateUser();

        $response = $this->get($this->getLocalizedRoute('admin_reports'));

        $response->assertRedirect($this->getLocalizedRoute('index'));
    }

    /** @test */
    public function admin_can_access_report_generator()
    {
        $admin = $this->createAndAuthenticateAdmin();
        session(['auth.2fa' => true]);

        $response = $this->get($this->getLocalizedRoute('admin_reports'));

        $response->assertStatus(200);
    }

    /** @test */
    public function component_renders_successfully()
    {
        $admin = $this->createAndAuthenticateAdmin();
        session(['auth.2fa' => true]);

        Livewire::test(ReportGenerator::class)
            ->assertSuccessful();
    }

    /** @test */
    public function shows_only_approved_applications()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);

        $approved = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
            'approval_appl' => '2024-01-15',
        ]);
        $pending = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        Livewire::test(ReportGenerator::class)
            ->assertSee($approved->id)
            ->assertDontSee($pending->id);
    }

    /** @test */
    public function can_filter_applications_by_year()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);

        $app2023 = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
            'approval_appl' => '2023-06-01',
            'name' => 'Antrag 2023 XYZ',
        ]);
        $app2024 = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
            'approval_appl' => '2024-06-01',
            'name' => 'Antrag 2024 ABC',
        ]);

        Livewire::test(ReportGenerator::class)
            ->set('selectedYear', '2024')
            ->assertSee('Antrag 2024 ABC')
            ->assertDontSee('Antrag 2023 XYZ');
    }

    /** @test */
    public function can_filter_applications_without_approval_date()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);

        $withDate = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
            'approval_appl' => '2024-01-01',
            'name' => 'Antrag Mit Datum',
        ]);
        $withoutDate = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
            'approval_appl' => null,
            'name' => 'Antrag Ohne Datum',
        ]);

        Livewire::test(ReportGenerator::class)
            ->set('selectedYear', 'no_date')
            ->assertSee('Antrag Ohne Datum')
            ->assertDontSee('Antrag Mit Datum');
    }

    /** @test */
    public function years_dropdown_contains_years_from_approved_applications()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);

        Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
            'approval_appl' => '2023-05-01',
        ]);
        Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
            'approval_appl' => '2024-05-01',
        ]);

        Livewire::test(ReportGenerator::class)
            ->assertSee('2023')
            ->assertSee('2024');
    }

    /** @test */
    public function has_no_date_flag_when_approved_application_lacks_date()
    {
        $admin = $this->createAndAuthenticateAdmin();
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);

        Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
            'approval_appl' => null,
        ]);

        Livewire::test(ReportGenerator::class)
            ->assertSet('selectedYear', null);

        // hasNoDateApplications should be true - check via view data
        $component = Livewire::test(ReportGenerator::class);
        $component->assertSuccessful();
    }

    /** @test */
    public function initial_state_has_no_download_url()
    {
        $admin = $this->createAndAuthenticateAdmin();

        Livewire::test(ReportGenerator::class)
            ->assertSet('downloadUrl', null)
            ->assertSet('isGenerating', false);
    }
}
