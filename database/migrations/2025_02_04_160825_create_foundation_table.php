<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foundation', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('strasse');
			$table->string('ort');
			$table->string('land');
			$table->date('nextCouncilMeeting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foundation');
    }
};
