<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if an index exists on a table
     */
    private function indexExists($table, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM $table WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check and add indexes only if they don't exist
        
        // Enclosures - check if index exists
        if (!$this->indexExists('enclosures', 'enclosures_application_id_index')) {
            Schema::table('enclosures', function (Blueprint $table) {
                $table->index('application_id', 'enclosures_application_id_index');
            });
        }

        // Costs - check if index exists
        if (!$this->indexExists('costs', 'costs_application_id_index')) {
            Schema::table('costs', function (Blueprint $table) {
                $table->index('application_id', 'costs_application_id_index');
            });
        }

        // Financings - check if index exists
        if (!$this->indexExists('financings', 'financings_application_id_index')) {
            Schema::table('financings', function (Blueprint $table) {
                $table->index('application_id', 'financings_application_id_index');
            });
        }

        // Educations
        if (!$this->indexExists('educations', 'educations_application_id_index')) {
            Schema::table('educations', function (Blueprint $table) {
                $table->index('application_id', 'educations_application_id_index');
            });
        }
        if (!$this->indexExists('educations', 'educations_user_id_index')) {
            Schema::table('educations', function (Blueprint $table) {
                $table->index('user_id', 'educations_user_id_index');
            });
        }

        // Accounts
        if (!$this->indexExists('accounts', 'accounts_application_id_index')) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->index('application_id', 'accounts_application_id_index');
            });
        }
        if (!$this->indexExists('accounts', 'accounts_user_id_index')) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->index('user_id', 'accounts_user_id_index');
            });
        }

        // Parents
        if (!$this->indexExists('parents', 'parents_user_id_index')) {
            Schema::table('parents', function (Blueprint $table) {
                $table->index('user_id', 'parents_user_id_index');
            });
        }

        // Siblings
        if (!$this->indexExists('siblings', 'siblings_user_id_index')) {
            Schema::table('siblings', function (Blueprint $table) {
                $table->index('user_id', 'siblings_user_id_index');
            });
        }

        // Messages
        if (!$this->indexExists('messages', 'messages_application_id_index')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->index('application_id', 'messages_application_id_index');
            });
        }
        if (!$this->indexExists('messages', 'messages_user_id_index')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->index('user_id', 'messages_user_id_index');
            });
        }

        // Cost Darlehens
        if (!$this->indexExists('cost_darlehens', 'cost_darlehens_application_id_index')) {
            Schema::table('cost_darlehens', function (Blueprint $table) {
                $table->index('application_id', 'cost_darlehens_application_id_index');
            });
        }
        if (!$this->indexExists('cost_darlehens', 'cost_darlehens_user_id_index')) {
            Schema::table('cost_darlehens', function (Blueprint $table) {
                $table->index('user_id', 'cost_darlehens_user_id_index');
            });
        }

        // Financing Organisations
        if (!$this->indexExists('financing_organisations', 'financing_organisations_application_id_index')) {
            Schema::table('financing_organisations', function (Blueprint $table) {
                $table->index('application_id', 'financing_organisations_application_id_index');
            });
        }
        if (!$this->indexExists('financing_organisations', 'financing_organisations_user_id_index')) {
            Schema::table('financing_organisations', function (Blueprint $table) {
                $table->index('user_id', 'financing_organisations_user_id_index');
            });
        }
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

        Schema::table('financings', function (Blueprint $table) {
            $table->dropIndex('financings_application_id_index');
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
            $table->dropIndex('messages_user_id_index');
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