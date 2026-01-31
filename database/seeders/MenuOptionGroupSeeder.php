<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuOptionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // =========================
            // 🍽️ NASI GORENG (menu_id = 1)
            // =========================
            [
                'menu_id'          => 1,
                'option_group_id'  => 1, // Level Pedas
                'is_required'      => true,
                'min_choice'       => 1,
                'max_choice'       => 1,
                'sort_order'       => 1,
            ],
            [
                'menu_id'          => 1,
                'option_group_id'  => 2, // Topping
                'is_required'      => false,
                'min_choice'       => null,
                'max_choice'       => 3,
                'sort_order'       => 2,
            ],

            // =========================
            // 🍗 AYAM GEPREK (menu_id = 2)
            // =========================
            [
                'menu_id'          => 3,
                'option_group_id'  => 1, // Level Pedas
                'is_required'      => true,
                'min_choice'       => 1,
                'max_choice'       => 1,
                'sort_order'       => 1,
            ],
            [
                'menu_id'          => 3,
                'option_group_id'  => 2, // Topping
                'is_required'      => false,
                'min_choice'       => null,
                'max_choice'       => 2,
                'sort_order'       => 2,
            ],

            // =========================
            // 🥤 ES TEH (menu_id = 3)
            // =========================
            [
                'menu_id'          => 16,
                'option_group_id'  => 3, // Ukuran
                'is_required'      => true,
                'min_choice'       => 1,
                'max_choice'       => 1,
                'sort_order'       => 1,
            ],
            [
                'menu_id'          => 16,
                'option_group_id'  => 4, // Gula
                'is_required'      => false,
                'min_choice'       => null,
                'max_choice'       => 1,
                'sort_order'       => 2,
            ],
        ];

        DB::table('menu_option_groups')->insert($data);
    }
}
