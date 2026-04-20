<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logins', function (Blueprint $table) {
            $table->text('ip_address')->change();
        });

        DB::table('logins')->orderBy('id')->chunk(100, function ($logins) {
            foreach ($logins as $login) {
                DB::table('logins')
                    ->where('id', $login->id)
                    ->update(['ip_address' => Crypt::encryptString($login->ip_address)]);
            }
        });
    }

    public function down(): void
    {
        DB::table('logins')->orderBy('id')->chunk(100, function ($logins) {
            foreach ($logins as $login) {
                try {
                    $plain = Crypt::decryptString($login->ip_address);
                } catch (\Exception) {
                    $plain = $login->ip_address;
                }
                DB::table('logins')
                    ->where('id', $login->id)
                    ->update(['ip_address' => $plain]);
            }
        });

        Schema::table('logins', function (Blueprint $table) {
            $table->string('ip_address', 50)->change();
        });
    }
};
