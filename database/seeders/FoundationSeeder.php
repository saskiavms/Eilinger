<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoundationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('foundation')->insert([
            'name' => 'Eilinger Stiftung',
            'strasse' => 'Seeweg 45',
            'ort' => '8264 Eschenz',
			'land' => 'Schweiz'
        ]);
    }
}
