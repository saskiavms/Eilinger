<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Livewire\Antrag\CostFormDarlehen;
use App\Models\Application;
use App\Models\CostDarlehen;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class CostFormDarlehenTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    protected function setUp(): void
    {
        parent::setUp();
        session()->flush();
    }

    private function makeApplication($user): Application
    {
        $currency = Currency::first();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => \App\Enums\ApplStatus::NOTSEND,
        ]);
        session(['appl_id' => $application->id]);
        return $application;
    }

    /** @test */
    public function cost_form_darlehen_renders_with_one_empty_row()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(CostFormDarlehen::class)
            ->assertSuccessful()
            ->assertSet('costs', fn($costs) => count($costs) === 1);
    }

    /** @test */
    public function required_fields_show_validation_errors_when_empty()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(CostFormDarlehen::class)
            ->set('costs.0.cost_name', '')
            ->set('costs.0.cost_amount', '')
            ->call('saveCosts')
            ->assertHasErrors([
                'costs.0.cost_name',
                'costs.0.cost_amount',
            ]);
    }

    /** @test */
    public function cost_amount_must_be_numeric()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(CostFormDarlehen::class)
            ->set('costs.0.cost_name', 'Miete')
            ->set('costs.0.cost_amount', 'not-a-number')
            ->call('saveCosts')
            ->assertHasErrors(['costs.0.cost_amount' => 'numeric']);
    }

    /** @test */
    public function valid_cost_saves_to_database()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);

        Livewire::test(CostFormDarlehen::class)
            ->set('costs.0.cost_name', 'Miete')
            ->set('costs.0.cost_amount', 1200)
            ->call('saveCosts')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cost_darlehens', [
            'application_id' => $application->id,
            'user_id' => $user->id,
            'cost_name' => 'Miete',
            'cost_amount' => 1200,
            'is_draft' => false,
        ]);
    }

    /** @test */
    public function can_add_a_second_cost_row()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(CostFormDarlehen::class)
            ->assertSet('costs', fn($costs) => count($costs) === 1)
            ->call('addCost')
            ->assertSet('costs', fn($costs) => count($costs) === 2);
    }

    /** @test */
    public function can_remove_a_cost_row()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(CostFormDarlehen::class)
            ->call('addCost')
            ->assertSet('costs', fn($costs) => count($costs) === 2)
            ->call('removeCost', 1)
            ->assertSet('costs', fn($costs) => count($costs) === 1);
    }

    /** @test */
    public function total_is_calculated_correctly()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(CostFormDarlehen::class)
            ->set('costs.0.cost_amount', 1200)
            ->call('addCost')
            ->set('costs.1.cost_amount', 800)
            ->assertSet('total_amount', 2000.0);
    }

    /** @test */
    public function loads_existing_cost_entries_on_mount()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);

        CostDarlehen::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'cost_name' => 'Kursgebühren',
            'cost_amount' => 500,
            'is_draft' => false,
        ]);

        Livewire::test(CostFormDarlehen::class)
            ->assertSet('costs', fn($costs) =>
                count($costs) === 1 && $costs[0]['cost_name'] === 'Kursgebühren'
            );
    }
}
