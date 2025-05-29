<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function payment_belongs_to_application()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $payment = Payment::factory()->create(['application_id' => $application->id]);

        $this->assertInstanceOf(Application::class, $payment->application);
        $this->assertEquals($application->id, $payment->application->id);
    }

    /** @test */
    public function payment_requires_application_id()
    {
        $this->expectException(\Exception::class);
        Payment::factory()->create(['application_id' => null]);
    }

    /** @test */
    public function payment_stores_decimal_amounts_correctly()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $payment = Payment::factory()->create([
            'application_id' => $application->id,
            'amount' => 1234.56
        ]);

        $this->assertEquals(1234.56, $payment->amount);
        $this->assertEquals('1234.56', $payment->amount);
    }

    /** @test */
    public function payment_can_have_notes()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        
        $payment = Payment::factory()->create([
            'application_id' => $application->id,
            'notes' => 'First installment payment'
        ]);

        $this->assertEquals('First installment payment', $payment->notes);
    }

    /** @test */
    public function payment_date_is_cast_to_date()
    {
        $user = User::factory()->create();
        $application = Application::factory()->create(['user_id' => $user->id]);
        $payment = Payment::factory()->create([
            'application_id' => $application->id,
            'payment_date' => '2024-12-25'
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $payment->payment_date);
        $this->assertEquals('2024-12-25', $payment->payment_date->format('Y-m-d'));
    }
}
