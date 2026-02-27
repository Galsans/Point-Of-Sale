<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = DB::table('menus')->get()->keyBy('name');

        // Helper: cari menu by nama, return null jika tidak ada
        $menuId = fn(string $name) => $menus->get($name)?->id ?? null;
        $menuPrice = fn(string $name) => $menus->get($name)?->price ?? 0;

        // ── Data paket ──
        $packages = [

            // ─────────────────────────────────────
            // PAKET 1 — Paket Hemat A
            // ─────────────────────────────────────
            [
                'package' => [
                    'name'            => 'Paket Hemat A',
                    'description'     => 'Nasi + lauk + minuman segar. Cocok untuk makan siang!',
                    'badge'           => 'Terlaris',
                    'package_price'   => 35000,
                    'category'        => 'Makan Siang',
                    'is_active'       => true,
                    'sort_order'      => 1,
                    'available_from'  => '11:00:00',
                    'available_until' => '15:00:00',
                ],
                'items' => [
                    ['name' => 'Nasi Goreng',   'category' => 'Makanan',  'quantity' => 1],
                    ['name' => 'Es Teh Manis',  'category' => 'Minuman',  'quantity' => 1],
                    ['name' => 'Pudding Coklat', 'category' => 'Dessert',  'quantity' => 1],
                ],
            ],

            // ─────────────────────────────────────
            // PAKET 2 — Paket Sarapan Pagi
            // ─────────────────────────────────────
            [
                'package' => [
                    'name'            => 'Paket Sarapan Pagi',
                    'description'     => 'Mulai hari dengan semangat! Sarapan lengkap dan bergizi.',
                    'badge'           => 'Baru',
                    'package_price'   => 28000,
                    'category'        => 'Sarapan',
                    'is_active'       => true,
                    'sort_order'      => 2,
                    'available_from'  => '07:00:00',
                    'available_until' => '10:30:00',
                ],
                'items' => [
                    ['name' => 'Roti Bakar',    'category' => 'Makanan',  'quantity' => 2],
                    ['name' => 'Telur Dadar',   'category' => 'Makanan',  'quantity' => 1],
                    ['name' => 'Kopi Susu',     'category' => 'Minuman',  'quantity' => 1],
                ],
            ],

            // ─────────────────────────────────────
            // PAKET 3 — Paket Premium
            // ─────────────────────────────────────
            [
                'package' => [
                    'name'            => 'Paket Premium',
                    'description'     => 'Pengalaman makan terbaik. Steak pilihan dengan dessert eksklusif.',
                    'badge'           => null,
                    'package_price'   => 120000,
                    'category'        => 'Makan Malam',
                    'is_active'       => true,
                    'sort_order'      => 3,
                    'available_from'  => '17:00:00',
                    'available_until' => '22:00:00',
                ],
                'items' => [
                    ['name' => 'Steak Beef',    'category' => 'Makanan',  'quantity' => 1],
                    ['name' => 'Jus Alpukat',   'category' => 'Minuman',  'quantity' => 1],
                    ['name' => 'Tiramisu',      'category' => 'Dessert',  'quantity' => 1],
                ],
            ],

            // ─────────────────────────────────────
            // PAKET 4 — Paket Keluarga
            // ─────────────────────────────────────
            [
                'package' => [
                    'name'            => 'Paket Keluarga',
                    'description'     => 'Porsi besar untuk 2-3 orang. Hemat dan mengenyangkan!',
                    'badge'           => 'Hemat',
                    'package_price'   => 95000,
                    'category'        => 'Makan Siang',
                    'is_active'       => true,
                    'sort_order'      => 4,
                    'available_from'  => null,
                    'available_until' => null,
                ],
                'items' => [
                    ['name' => 'Nasi Goreng',   'category' => 'Makanan',  'quantity' => 2],
                    ['name' => 'Ayam Bakar',    'category' => 'Makanan',  'quantity' => 2],
                    ['name' => 'Es Teh Manis',  'category' => 'Minuman',  'quantity' => 3],
                    ['name' => 'Pudding Coklat', 'category' => 'Dessert',  'quantity' => 2],
                ],
            ],

            // ─────────────────────────────────────
            // PAKET 5 — Paket Ngopi Santai
            // ─────────────────────────────────────
            [
                'package' => [
                    'name'            => 'Paket Ngopi Santai',
                    'description'     => 'Kopi + snack ringan. Pas buat nongkrong sore hari.',
                    'badge'           => null,
                    'package_price'   => 42000,
                    'category'        => 'Sore',
                    'is_active'       => true,
                    'sort_order'      => 5,
                    'available_from'  => '14:00:00',
                    'available_until' => '18:00:00',
                ],
                'items' => [
                    ['name' => 'Kopi Susu',         'category' => 'Minuman',  'quantity' => 1],
                    ['name' => 'Roti Bakar',         'category' => 'Makanan',  'quantity' => 1],
                    ['name' => 'Pudding Coklat',     'category' => 'Dessert',  'quantity' => 1],
                ],
            ],

            // ─────────────────────────────────────
            // PAKET 6 — Non-aktif (contoh paket tidak tampil)
            // ─────────────────────────────────────
            [
                'package' => [
                    'name'            => 'Paket Spesial Ramadan',
                    'description'     => 'Paket buka puasa lengkap. (Sementara tidak aktif)',
                    'badge'           => null,
                    'package_price'   => 55000,
                    'category'        => 'Spesial',
                    'is_active'       => false, // ← tidak tampil di QR menu
                    'sort_order'      => 6,
                    'available_from'  => '17:30:00',
                    'available_until' => '20:00:00',
                ],
                'items' => [
                    ['name' => 'Nasi Goreng',   'category' => 'Makanan',  'quantity' => 1],
                    ['name' => 'Ayam Bakar',    'category' => 'Makanan',  'quantity' => 1],
                    ['name' => 'Es Teh Manis',  'category' => 'Minuman',  'quantity' => 1],
                    ['name' => 'Jus Alpukat',   'category' => 'Minuman',  'quantity' => 1],
                    ['name' => 'Tiramisu',      'category' => 'Dessert',  'quantity' => 1],
                ],
            ],

        ];

        // ── Insert ke database ──
        foreach ($packages as $data) {
            $pkg = $data['package'];

            // Hitung original_price dari total harga satuan menu
            $originalPrice = 0;
            foreach ($data['items'] as $item) {
                $originalPrice += $menuPrice($item['name']) * $item['quantity'];
            }

            // Jika original_price 0 (menu belum ada), fallback ke package_price + estimasi
            // Ini agar seeder tetap jalan meski menu belum di-seed
            if ($originalPrice === 0) {
                $originalPrice = $pkg['package_price'] * 1.1; // asumsi hemat 10%
            }

            $offerId = DB::table('price_offers')->insertGetId([
                'name'            => $pkg['name'],
                'description'     => $pkg['description'],
                'badge'           => $pkg['badge'],
                'package_price'   => $pkg['package_price'],
                'original_price'  => $originalPrice,
                'image'           => null,
                'category'        => $pkg['category'],
                'is_active'       => $pkg['is_active'],
                'sort_order'      => $pkg['sort_order'],
                'available_from'  => $pkg['available_from'],
                'available_until' => $pkg['available_until'],
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);

            // Insert items paket
            foreach ($data['items'] as $index => $item) {
                $linkedMenuId    = $menuId($item['name']);
                $linkedMenuPrice = $menuPrice($item['name']);

                DB::table('price_offer_items')->insert([
                    'price_offer_id' => $offerId,
                    'menu_id'        => $linkedMenuId ?? 1, // fallback ke menu pertama jika tidak ditemukan
                    'item_name'      => $item['name'],
                    'item_price'     => $linkedMenuPrice > 0 ? $linkedMenuPrice : 10000,
                    'category'       => $item['category'],
                    'quantity'       => $item['quantity'],
                    'sort_order'     => $index,
                    'created_at'     => Carbon::now(),
                    'updated_at'     => Carbon::now(),
                ]);
            }

            $this->command->info("✅ Paket \"{$pkg['name']}\" berhasil dibuat ({$offerId})");
        }

        $this->command->info('');
        $this->command->info('🎉 PriceOfferSeeder selesai! ' . count($packages) . ' paket berhasil di-seed.');
        $this->command->warn('⚠️  Pastikan nama menu di seeder sesuai dengan nama menu di tabel menus Anda.');
    }
}
