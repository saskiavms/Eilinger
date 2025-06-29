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
        Schema::table('foundation', function (Blueprint $table) {
            $table->text('nextCouncilMeetingNote_de')->nullable()->after('nextCouncilMeeting');
            $table->text('nextCouncilMeetingNote_en')->nullable()->after('nextCouncilMeetingNote_de');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foundation', function (Blueprint $table) {
            $table->dropColumn(['nextCouncilMeetingNote_de', 'nextCouncilMeetingNote_en']);
        });
    }
};
