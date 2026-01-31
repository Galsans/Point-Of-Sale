<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
            // Bagian Ayam
            [
                'option_group_id' => 1,
                'name' => 'Dada',
                'extra_price' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'option_group_id' => 1,
                'name' => 'Paha',
                'extra_price' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Sambal
            [
                'option_group_id' => 2,
                'name' => 'Pakai Sambal',
                'extra_price' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'option_group_id' => 2,
                'name' => 'Tanpa Sambal',
                'extra_price' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Suhu
            [
                'option_group_id' => 3,
                'name' => 'Es',
                'extra_price' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'option_group_id' => 3,
                'name' => 'Panas',
                'extra_price' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Gula
            [
                'option_group_id' => 4,
                'name' => 'Normal Sugar',
                'extra_price' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'option_group_id' => 4,
                'name' => 'Less Sugar',
                'extra_price' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'option_group_id' => 4,
                'name' => 'No Sugar',
                'extra_price' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // tamhan

        ]);
    }
}
