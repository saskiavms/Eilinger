<?php

namespace Tests\Unit\Models;

use App\Models\Application;
use App\Models\User;
use App\Models\Account;
use App\Models\Cost;
use App\Models\Education;
use App\Models\Financing;
use App\Models\Enclosure;
use App\Enums\ApplStatus;
use App\Enums\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function application_belongs_to_user()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $application->user);
        $this->assertEquals($user->id, $application->user->id);
    }

    /** @test */
    public function application_has_one_account()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        
        // Create account directly in database since factory doesn't exist
        $account = Account::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'name_bank' => 'Test Bank',
            'IBAN' => 'DE89370400440532013000',
            'is_draft' => false
        ]);

        $this->assertInstanceOf(Account::class, $application->account);
        $this->assertEquals($account->id, $application->account->id);
    }

    /** @test */
    public function application_has_one_cost()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        
        // Create cost directly since factory doesn't exist
        $cost = Cost::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'semester_fees' => 1000.00,
            'is_draft' => false
        ]);

        $this->assertInstanceOf(Cost::class, $application->cost);
        $this->assertEquals($cost->id, $application->cost->id);
    }

    /** @test */
    public function application_has_one_education()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        
        // Create education directly since factory doesn't exist
        $education = Education::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'education' => 'Universität',
            'name' => 'Test University',
            'is_draft' => false
        ]);

        $this->assertInstanceOf(Education::class, $application->education);
        $this->assertEquals($education->id, $application->education->id);
    }

    /** @test */
    public function application_has_one_financing()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        
        // Create financing directly since factory doesn't exist
        $financing = Financing::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'personal_contribution' => 500,
            'is_draft' => false
        ]);

        $this->assertInstanceOf(Financing::class, $application->financing);
        $this->assertEquals($financing->id, $application->financing->id);
    }

    /** @test */
    public function application_has_many_enclosures()
    {
        $application = Application::factory()->create();
        
        // Create enclosure directly since factory doesn't exist
        $enclosure = Enclosure::create([
            'application_id' => $application->id,
            'filename' => 'test.pdf',
            'path' => '/uploads/test.pdf',
            'is_draft' => false
        ]);

        $this->assertTrue($application->enclosures()->exists());
        $this->assertInstanceOf(Enclosure::class, $application->enclosures->first());
    }

    /** @test */
    public function application_status_can_be_set_and_retrieved()
    {
        $application = Application::factory()->create(['appl_status' => ApplStatus::NOTSEND]);

        $this->assertEquals(ApplStatus::NOTSEND, $application->appl_status);

        $application->appl_status = ApplStatus::PENDING;
        $application->save();

        $this->assertEquals(ApplStatus::PENDING, $application->fresh()->appl_status);
    }

    /** @test */
    public function application_form_type_can_be_set_and_retrieved()
    {
        $application = Application::factory()->create(['form' => Form::Stipendium]);

        $this->assertEquals(Form::Stipendium, $application->form);

        $application->form = Form::Darlehen;
        $application->save();

        $this->assertEquals(Form::Darlehen, $application->fresh()->form);
    }

    /** @test */
    public function application_can_store_amounts()
    {
        $application = Application::factory()->create([
            'req_amount' => 5000.00,
            'calc_amount' => 4500.00
        ]);

        $this->assertEquals(5000.00, $application->req_amount);
        $this->assertEquals(4500.00, $application->calc_amount);
    }

    /** @test */
    public function application_tracks_payment_information()
    {
        $application = Application::factory()->create([
            'payment_amount' => null,
            'payment_date' => null
        ]);

        $this->assertNull($application->payment_amount);
        $this->assertNull($application->payment_date);

        // Simulate payment
        $paymentAmount = 4500.00;
        $paymentDate = now()->toDateString();

        $application->update([
            'payment_amount' => $paymentAmount,
            'payment_date' => $paymentDate
        ]);

        $this->assertEquals($paymentAmount, $application->fresh()->payment_amount);
        $this->assertEquals($paymentDate, $application->fresh()->payment_date->toDateString());
    }

    /** @test */
    public function application_has_start_and_end_dates()
    {
        $startDate = now()->toDateString();
        $endDate = now()->addMonths(6)->toDateString();

        $application = Application::factory()->create([
            'start_appl' => $startDate,
            'end_appl' => $endDate
        ]);

        $this->assertEquals($startDate, $application->start_appl->toDateString());
        $this->assertEquals($endDate, $application->end_appl->toDateString());
    }

    /** @test */
    public function application_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Try to create application without required fields
        Application::create([]);
    }

    /** @test */
    public function application_can_be_soft_deleted()
    {
        $application = Application::factory()->create();
        $applicationId = $application->id;

        $application->delete();

        // Check that it's soft deleted
        $this->assertNull(Application::find($applicationId));
        $this->assertNotNull(Application::withTrashed()->find($applicationId));
    }

    /** @test */
    public function application_maintains_audit_trail()
    {
        $application = Application::factory()->create();

        $this->assertNotNull($application->created_at);
        $this->assertNotNull($application->updated_at);

        // Update and check timestamps
        $originalUpdatedAt = $application->updated_at;
        sleep(1);
        $application->touch();

        $this->assertNotEquals($originalUpdatedAt, $application->fresh()->updated_at);
    }
}