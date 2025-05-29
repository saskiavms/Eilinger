<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Application;
use App\Models\Payment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing payment data from applications to payments table
        $applications = Application::whereNotNull('payment_amount')
            ->whereNotNull('payment_date')
            ->get();

        foreach ($applications as $application) {
            Payment::create([
                'application_id' => $application->id,
                'amount' => $application->payment_amount,
                'payment_date' => $application->payment_date,
                'notes' => 'Migrated from application table',
            ]);
        }

        // Remove payment columns from applications table
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['payment_amount', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add payment columns back to applications table
        Schema::table('applications', function (Blueprint $table) {
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->date('payment_date')->nullable();
        });

        // Migrate the first payment back to applications table
        $payments = Payment::with('application')->get();
        foreach ($payments as $payment) {
            if ($payment->application) {
                $payment->application->update([
                    'payment_amount' => $payment->amount,
                    'payment_date' => $payment->payment_date,
                ]);
            }
        }

        // Remove all payments
        Payment::truncate();
    }
};
