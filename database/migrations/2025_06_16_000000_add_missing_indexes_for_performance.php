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
        // Add critical missing index on enclosures.application_id
        Schema::table('enclosures', function (Blueprint $table) {
            $table->index('application_id', 'enclosures_application_id_index');
        });

        // Add index on costs.application_id if missing
        Schema::table('costs', function (Blueprint $table) {
            $table->index('application_id', 'costs_application_id_index');
        });

        // Add index on financing.application_id if missing  
        Schema::table('financing', function (Blueprint $table) {
            $table->index('application_id', 'financing_application_id_index');
        });

        // Add indexes on other tables that might be missing them
        Schema::table('educations', function (Blueprint $table) {
            $table->index('application_id', 'educations_application_id_index');
            $table->index('user_id', 'educations_user_id_index');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->index('application_id', 'accounts_application_id_index');
            $table->index('user_id', 'accounts_user_id_index');
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->index('user_id', 'parents_user_id_index');
        });

        Schema::table('siblings', function (Blueprint $table) {
            $table->index('user_id', 'siblings_user_id_index');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index('application_id', 'messages_application_id_index');
            $table->index('sender_id', 'messages_sender_id_index');
        });

        Schema::table('cost_darlehens', function (Blueprint $table) {
            $table->index('application_id', 'cost_darlehens_application_id_index');
            $table->index('user_id', 'cost_darlehens_user_id_index');
        });

        Schema::table('financing_organisations', function (Blueprint $table) {
            $table->index('application_id', 'financing_organisations_application_id_index');
            $table->index('user_id', 'financing_organisations_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enclosures', function (Blueprint $table) {
            $table->dropIndex('enclosures_application_id_index');
        });

        Schema::table('costs', function (Blueprint $table) {
            $table->dropIndex('costs_application_id_index');
        });

        Schema::table('financing', function (Blueprint $table) {
            $table->dropIndex('financing_application_id_index');
        });

        Schema::table('educations', function (Blueprint $table) {
            $table->dropIndex('educations_application_id_index');
            $table->dropIndex('educations_user_id_index');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->dropIndex('accounts_application_id_index');
            $table->dropIndex('accounts_user_id_index');
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->dropIndex('parents_user_id_index');
        });

        Schema::table('siblings', function (Blueprint $table) {
            $table->dropIndex('siblings_user_id_index');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_application_id_index');
            $table->dropIndex('messages_sender_id_index');
        });

        Schema::table('cost_darlehens', function (Blueprint $table) {
            $table->dropIndex('cost_darlehens_application_id_index');
            $table->dropIndex('cost_darlehens_user_id_index');
        });

        Schema::table('financing_organisations', function (Blueprint $table) {
            $table->dropIndex('financing_organisations_application_id_index');
            $table->dropIndex('financing_organisations_user_id_index');
        });
    }
};