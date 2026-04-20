<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fraud_signals', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('severity');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('related_user_id')->nullable();
            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('related_application_id')->nullable();

            // Minimal hint for admins — no raw personal data
            $table->json('details')->nullable();

            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by_id')->nullable();
            $table->boolean('is_false_positive')->default(false);

            $table->timestamps();

            $table->index('type');
            $table->index('severity');
            $table->index('user_id');
            $table->index('related_user_id');

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('related_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('application_id')->references('id')->on('applications')->nullOnDelete();
            $table->foreign('related_application_id')->references('id')->on('applications')->nullOnDelete();
            $table->foreign('reviewed_by_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fraud_signals');
    }
};
