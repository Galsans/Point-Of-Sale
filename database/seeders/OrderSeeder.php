<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $year = now()->year;

        // Ambil order terakhir di tahun ini
        $lastNumber = DB::table('orders')
            ->where('order_year', $year)
            ->max('order_number') ?? 0;

        $ordersData = [
            [
                'customer_name'    => 'Budi Santoso',
                'customer_email'   => 'budi@example.com',
                'customer_phone'   => '081234567890',
                'status'           => 'completed',
                'subtotal'         => 100000,
                'table_id'         => 1,
                'tax_amount'       => 10000,
                'discount_amount'  => 5000,
                'service_fee'      => 2000,
                'total_price'      => 107000,
            ],
            [
                'customer_name'    => 'Siti Aminah',
                'customer_email'   => 'siti@example.com',
                'customer_phone'   => '082345678901',
                'status'           => 'paid',
                'subtotal'         => 75000,
                'table_id'         => 2,
                'tax_amount'       => 7500,
                'discount_amount'  => 0,
                'service_fee'      => 2000,
                'total_price'      => 84500,
            ],
            [
                'customer_name'    => 'Andi Wijaya',
                'customer_email'   => null,
                'customer_phone'   => null,
                'status'           => 'pending',
                'subtotal'         => 50000,
                'table_id'         => 3,
                'tax_amount'       => 0,
                'discount_amount'  => 0,
                'service_fee'      => 0,
                'total_price'      => 50000,
            ],
            [
                'customer_name'    => 'Dewi Lestari',
                'customer_email'   => 'dewi@example.com',
                'customer_phone'   => '083456789012',
                'status'           => 'cancelled',
                'subtotal'         => 120000,
                'table_id'         => 4,
                'tax_amount'       => 12000,
                'discount_amount'  => 20000,
                'service_fee'      => 3000,
                'total_price'      => 115000,
            ],
        ];

        $now = now();
        $insertData = [];

        foreach ($ordersData as $data) {
            $lastNumber++;

            $orderNumber = $lastNumber;
            $orderCode = sprintf(
                'ORD-%d-%08d',
                $year,
                $orderNumber
            );

            $insertData[] = array_merge($data, [
                'order_year'   => $year,
                'order_number' => $orderNumber,
                'order_code'   => $orderCode,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        DB::table('orders')->insert($insertData);
    }
}
