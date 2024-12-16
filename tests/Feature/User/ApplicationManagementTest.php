<?php

namespace Tests\Feature\User;

use App\Enums\ApplStatus;
use App\Models\Application;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;
use Tests\Traits\LocalizedTestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApplicationManagementTest extends TestCase
{
    use RefreshDatabase, WithAuthUser, LocalizedTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->createAndAuthenticateUser();
    }

    public function test_user_can_view_own_applications()
    {
        $application = Application::factory()->create([
            'user_id' => $this->authUser->id,
            'appl_status' => ApplStatus::NOTSEND
        ]);

        $response = $this->get($this->getLocalizedRoute('user_antraege'));

        $response->assertStatus(200);
        $response->assertSee($application->name);
    }

    public function test_user_cannot_view_others_applications()
    {
        $otherUser = User::factory()->create();
        $otherApplication = Application::factory()->create([
            'user_id' => $otherUser->id
        ]);

        $response = $this->get($this->getLocalizedRoute('user_antraege'));

        $response->assertStatus(200);
        $response->assertDontSee($otherApplication->name);
    }

    public function test_user_can_edit_draft_application()
    {
        $application = Application::factory()->create([
            'user_id' => $this->authUser->id,
            'appl_status' => ApplStatus::NOTSEND
        ]);

        $response = $this->get(route('user_antrag', [
            'locale' => app()->getLocale(),
            'application_id' => $application->id
        ]));

        $response->assertStatus(200);
    }

    public function test_user_can_edit_submitted_application()
    {
        $application = Application::factory()->create([
            'user_id' => $this->authUser->id,
            'appl_status' => ApplStatus::PENDING
        ]);

        $response = $this->get(route('user_antrag', [
            'locale' => app()->getLocale(),
            'application_id' => $application->id
        ]));

        $response->assertStatus(200);
    }
}
