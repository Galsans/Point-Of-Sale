<?php

namespace Database\Seeders;

use App\Models\Pajak;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pajak::create([
            'ppn' => 10,
            'service_fee' => 50
        ]);
    }
}
