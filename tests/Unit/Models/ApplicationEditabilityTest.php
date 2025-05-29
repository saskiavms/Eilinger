<?php

namespace Tests\Unit\Models;

use App\Enums\ApplStatus;
use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationEditabilityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function application_is_editable_when_status_is_not_approved()
    {
        $user = User::factory()->create();

        // Test all non-approved statuses
        $editableStatuses = [
            ApplStatus::NOTSEND,
            ApplStatus::PENDING,
            ApplStatus::WAITING,
            ApplStatus::COMPLETE,
            ApplStatus::BLOCKED,
            ApplStatus::FINISHED,
        ];

        foreach ($editableStatuses as $status) {
            $application = Application::factory()->create([
                'user_id' => $user->id,
                'appl_status' => $status,
            ]);

            $this->assertTrue($application->isEditable(), "Application with status {$status->name} should be editable");
        }
    }

    /** @test */
    public function application_is_not_editable_when_approved()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::APPROVED,
        ]);

        $this->assertFalse($application->isEditable());
    }

    /** @test */
    public function application_editability_changes_when_status_changes()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'appl_status' => ApplStatus::PENDING,
        ]);

        // Initially editable
        $this->assertTrue($application->isEditable());

        // Change to approved - should not be editable
        $application->appl_status = ApplStatus::APPROVED;
        $application->save();

        $this->assertFalse($application->fresh()->isEditable());

        // Change back to pending - should be editable again
        $application->appl_status = ApplStatus::PENDING;
        $application->save();

        $this->assertTrue($application->fresh()->isEditable());
    }
}
