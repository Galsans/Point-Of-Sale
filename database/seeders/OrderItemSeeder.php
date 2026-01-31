<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderItems = [
            // ==========================
            // INV-2026-0001
            // ==========================
            [
                'order_id'  => 1,
                'menu_id'   => 1, // contoh: Nasi Goreng
                'qty'       => 2,
                'price'     => 25000,
                'subtotal'  => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id'  => 1,
                'menu_id'   => 2, // contoh: Es Teh
                'qty'       => 2,
                'price'     => 10000,
                'subtotal'  => 20000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================
            // INV-2026-0002
            // ==========================
            [
                'order_id'  => 2,
                'menu_id'   => 3, // contoh: Mie Goreng
                'qty'       => 3,
                'price'     => 20000,
                'subtotal'  => 60000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================
            // INV-2026-0003
            // ==========================
            [
                'order_id'  => 3,
                'menu_id'   => 1,
                'qty'       => 1,
                'price'     => 50000,
                'subtotal'  => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================
            // INV-2026-0004
            // ==========================
            [
                'order_id'  => 4,
                'menu_id'   => 4, // contoh: Ayam Bakar
                'qty'       => 2,
                'price'     => 60000,
                'subtotal'  => 120000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('order_items')->insert($orderItems);
    }
}
