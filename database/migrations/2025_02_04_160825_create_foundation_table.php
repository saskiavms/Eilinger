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
			$table->string('name')->default('Eilinger Stiftung');
			$table->string('strasse')->default('Seeweg 45');
			$table->string('ort')->default('8264 Eschenz');
			$table->string('land')->default('Schweiz');
			$table->date('nextCouncilMeeting')->nullable();
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
