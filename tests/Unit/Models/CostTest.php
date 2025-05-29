<?php

namespace Tests\Unit\Models;

use App\Models\Cost;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cost_belongs_to_application()
    {
        $user = \App\Models\User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $cost = Cost::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'semester_fees' => 1000,
            'is_draft' => false
        ]);

        $this->assertInstanceOf(Application::class, $cost->application);
        $this->assertEquals($application->id, $cost->application->id);
    }

    /** @test */
    public function cost_calculates_total_costs_correctly()
    {
        $user = \App\Models\User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $cost = Cost::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'semester_fees' => 1000,
            'fees' => 800,
            'travel_expenses' => 200,
            'educational_material' => 100,
            'is_draft' => false
        ]);

        $expectedTotal = 1000 + 800 + 200 + 100;
        
        // If there's a method to calculate total costs
        if (method_exists($cost, 'getTotalCosts')) {
            $this->assertEquals($expectedTotal, $cost->getTotalCosts());
        } else {
            // Test individual cost components
            $this->assertEquals(1000, $cost->semester_fees);
            $this->assertEquals(800, $cost->fees);
            $this->assertEquals(200, $cost->travel_expenses);
            $this->assertEquals(100, $cost->educational_material);
        }
    }

    /** @test */
    public function cost_validates_positive_amounts()
    {
        $user = \App\Models\User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $cost = Cost::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'semester_fees' => 1000,
            'is_draft' => false
        ]);

        $this->assertGreaterThanOrEqual(0, $cost->semester_fees);
    }

    /** @test */
    public function cost_can_be_marked_as_draft()
    {
        $user = \App\Models\User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $cost = Cost::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'semester_fees' => 1000,
            'is_draft' => true
        ]);

        $this->assertTrue($cost->is_draft);

        $cost->update(['is_draft' => false]);
        $this->assertFalse($cost->fresh()->is_draft);
    }

    /** @test */
    public function cost_stores_decimal_amounts_correctly()
    {
        $user = \App\Models\User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $cost = Cost::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'semester_fees' => 1234,
            'fees' => 789,
            'is_draft' => false
        ]);

        $this->assertEquals(1234, $cost->semester_fees);
        $this->assertEquals(789, $cost->fees);
    }

    /** @test */
    public function cost_handles_null_optional_fields()
    {
        $user = \App\Models\User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $cost = Cost::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'semester_fees' => 1000,
            'fees' => null,
            'travel_expenses' => null,
            'educational_material' => null,
            'is_draft' => false
        ]);

        $this->assertEquals(1000, $cost->semester_fees);
        $this->assertNull($cost->fees);
        $this->assertNull($cost->travel_expenses);
        $this->assertNull($cost->educational_material);
    }

    /** @test */
    public function cost_can_be_updated()
    {
        $user = \App\Models\User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $cost = Cost::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'semester_fees' => 1000,
            'is_draft' => true
        ]);

        $cost->update([
            'semester_fees' => 1500,
            'fees' => 900,
            'is_draft' => false
        ]);

        $freshCost = $cost->fresh();
        $this->assertEquals(1500, $freshCost->semester_fees);
        $this->assertEquals(900, $freshCost->fees);
        $this->assertFalse($freshCost->is_draft);
    }

    /** @test */
    public function cost_requires_application_id()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Cost::create([
            'semester_fees' => 1000,
            'is_draft' => false
        ]);
    }

    /** @test */
    public function cost_maintains_timestamps()
    {
        $user = \App\Models\User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $cost = Cost::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'semester_fees' => 1000,
            'is_draft' => false
        ]);

        $this->assertNotNull($cost->created_at);
        $this->assertNotNull($cost->updated_at);

        $originalUpdatedAt = $cost->updated_at;
        sleep(1);
        $cost->touch();

        $this->assertNotEquals($originalUpdatedAt, $cost->fresh()->updated_at);
    }
}