<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('option_groups')->insert([
            [
                'id' => 1,
                'name' => 'Bagian Ayam',
                'type' => 'single',
                'description' => 'Pilihan bagian ayam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Sambal',
                'type' => 'single',
                'description' => 'Pilihan sambal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Suhu',
                'type' => 'single',
                'description' => 'Suhu minuman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Gula',
                'type' => 'single',
                'description' => 'Level gula',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Catatan',
                'type' => 'text',
                'description' => 'Catatan khusus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
