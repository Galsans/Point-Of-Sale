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
        $menuId    = fn(string $name) => $menus->get($name)?->id ?? null;
        $menuPrice = fn(string $name) => $menus->get($name)?->price ?? 0;

        // ── Data paket ──
        // Semua item_name sudah disinkronisasi dengan nama menu yang ada di MenuSeeder
        //
        // MAPPING PERUBAHAN dari versi lama:
        //   'Es Teh Manis'   → 'Lemon Tea'          (kategori Tea, paling mirip)
        //   'Kopi Susu'      → 'Gula Aren'           (kategori Coffee Fusion, kopi susu aren)
        //   'Jus Alpukat'    → 'Matcha Original'     (kategori Matcha, minuman premium)
        //   'Roti Bakar'     → 'Butter Croissant'    (kategori Sweet & Pastries, pastry sarapan)
        //   'Telur Dadar'    → 'Spicy Corn Ribs'     (kategori Starter, snack pendamping)
        //   'Steak Beef'     → 'Iga Bakar'           (kategori Main Course, main dish premium)
        //   'Pudding Coklat' → 'Cheese Cake'         (kategori Sweet & Pastries, dessert)
        //   'Tiramisu'       → 'Pain au Choco'       (kategori Sweet & Pastries, dessert premium)
        //   'Ayam Bakar'     → 'Ayam Bakar Bumbu Rujak' (nama lengkap yang ada di menu)

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
                    ['name' => 'Nasi Goreng',   'category' => 'Main Course',      'quantity' => 1],
                    ['name' => 'Lemon Tea',     'category' => 'Tea',              'quantity' => 1],
                    ['name' => 'Cheese Cake',   'category' => 'Sweet & Pastries', 'quantity' => 1],
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
                    ['name' => 'Butter Croissant', 'category' => 'Sweet & Pastries', 'quantity' => 2],
                    ['name' => 'Spicy Corn Ribs',  'category' => 'Starter Menu',     'quantity' => 1],
                    ['name' => 'Americano',         'category' => 'Coffee Enthusiasm', 'quantity' => 1],
                ],
            ],

            // ─────────────────────────────────────
            // PAKET 3 — Paket Premium
            // ─────────────────────────────────────
            [
                'package' => [
                    'name'            => 'Paket Premium',
                    'description'     => 'Pengalaman makan terbaik. Iga bakar pilihan dengan dessert eksklusif.',
                    'badge'           => null,
                    'package_price'   => 120000,
                    'category'        => 'Makan Malam',
                    'is_active'       => true,
                    'sort_order'      => 3,
                    'available_from'  => '17:00:00',
                    'available_until' => '22:00:00',
                ],
                'items' => [
                    ['name' => 'Iga Bakar',       'category' => 'Main Course',      'quantity' => 1],
                    ['name' => 'Matcha Original', 'category' => 'Matcha Series',    'quantity' => 1],
                    ['name' => 'Pain au Choco',   'category' => 'Sweet & Pastries', 'quantity' => 1],
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
                    ['name' => 'Nasi Goreng',           'category' => 'Main Course',      'quantity' => 2],
                    ['name' => 'Ayam Bakar Bumbu Rujak', 'category' => 'Main Course',      'quantity' => 2],
                    ['name' => 'Lemon Tea',             'category' => 'Tea',              'quantity' => 3],
                    ['name' => 'Cheese Cake',           'category' => 'Sweet & Pastries', 'quantity' => 2],
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
                    ['name' => 'Gula Aren',        'category' => 'Coffee Fusion',    'quantity' => 1],
                    ['name' => 'Butter Croissant', 'category' => 'Sweet & Pastries', 'quantity' => 1],
                    ['name' => 'Cheese Cake',      'category' => 'Sweet & Pastries', 'quantity' => 1],
                ],
            ],

            // ─────────────────────────────────────
            // PAKET 6 — Non-aktif
            // ─────────────────────────────────────
            [
                'package' => [
                    'name'            => 'Paket Spesial Ramadan',
                    'description'     => 'Paket buka puasa lengkap. (Sementara tidak aktif)',
                    'badge'           => null,
                    'package_price'   => 55000,
                    'category'        => 'Spesial',
                    'is_active'       => false,
                    'sort_order'      => 6,
                    'available_from'  => '17:30:00',
                    'available_until' => '20:00:00',
                ],
                'items' => [
                    ['name' => 'Nasi Goreng',           'category' => 'Main Course',      'quantity' => 1],
                    ['name' => 'Ayam Bakar Bumbu Rujak', 'category' => 'Main Course',      'quantity' => 1],
                    ['name' => 'Lemon Tea',             'category' => 'Tea',              'quantity' => 1],
                    ['name' => 'Matcha Original',       'category' => 'Matcha Series',    'quantity' => 1],
                    ['name' => 'Pain au Choco',         'category' => 'Sweet & Pastries', 'quantity' => 1],
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

            // Fallback jika menu belum ada
            if ($originalPrice === 0) {
                $originalPrice = $pkg['package_price'] * 1.1;
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
                    'menu_id'        => $linkedMenuId,
                    'item_name'      => $item['name'],
                    'item_price'     => $linkedMenuPrice > 0 ? $linkedMenuPrice : 10000,
                    'category'       => $item['category'],
                    'quantity'       => $item['quantity'],
                    'sort_order'     => $index,
                    'created_at'     => Carbon::now(),
                    'updated_at'     => Carbon::now(),
                ]);
            }

            $this->command->info("✅ Paket \"{$pkg['name']}\" berhasil dibuat (id: {$offerId})");
        }

        $this->command->info('');
        $this->command->info('🎉 PriceOfferSeeder selesai! ' . count($packages) . ' paket berhasil di-seed.');
    }
}
