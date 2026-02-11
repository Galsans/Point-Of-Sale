<?php

namespace Database\Seeders;

use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $menus = [

        //     // =========================
        //     // 🍽️ MAIN COURSE (1)
        //     // =========================
        //     [
        //         'category_id' => 1,
        //         'name' => 'Nasi Kebuli Ayam',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 34000,
        //         'is_available' => true,
        //         'description' => 'Nasi kebuli harum dengan ayam berbumbu rempah khas Timur Tengah.'
        //     ],
        //     [
        //         'category_id' => 1,
        //         'name' => 'Nasi Goreng Spesial',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 28000,
        //         'is_available' => true,
        //         'description' => 'Nasi goreng dengan telur, ayam, dan kerupuk.'
        //     ],

        //     // =========================
        //     // 🍚 RICE BOWL (2)
        //     // =========================
        //     [
        //         'category_id' => 2,
        //         'name' => 'Rice Bowl Ayam Teriyaki',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 25000,
        //         'is_available' => true,
        //         'description' => 'Ayam teriyaki manis gurih disajikan dengan nasi hangat.'
        //     ],
        //     [
        //         'category_id' => 2,
        //         'name' => 'Rice Bowl Beef Blackpepper',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 29000,
        //         'is_available' => true,
        //         'description' => 'Daging sapi empuk dengan saus lada hitam.'
        //     ],

        //     // =========================
        //     // 🍜 NOODLES (3)
        //     // =========================
        //     [
        //         'category_id' => 3,
        //         'name' => 'Mie Goreng Jawa',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 22000,
        //         'is_available' => true,
        //         'description' => 'Mie goreng khas Jawa dengan cita rasa manis gurih.'
        //     ],
        //     [
        //         'category_id' => 3,
        //         'name' => 'Mie Kuah Ayam',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 20000,
        //         'is_available' => true,
        //         'description' => 'Mie kuah hangat dengan ayam dan sayuran.'
        //     ],

        //     // =========================
        //     // 🍝 PASTA (4)
        //     // =========================
        //     [
        //         'category_id' => 4,
        //         'name' => 'Spaghetti Bolognese',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 32000,
        //         'is_available' => true,
        //         'description' => 'Spaghetti dengan saus daging tomat klasik.'
        //     ],
        //     [
        //         'category_id' => 4,
        //         'name' => 'Fettuccine Carbonara',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 35000,
        //         'is_available' => true,
        //         'description' => 'Pasta creamy dengan saus carbonara dan smoked beef.'
        //     ],

        //     // =========================
        //     // 🔥 GRILL (5)
        //     // =========================
        //     [
        //         'category_id' => 5,
        //         'name' => 'Chicken Steak',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 38000,
        //         'is_available' => true,
        //         'description' => 'Dada ayam panggang dengan saus barbeque.'
        //     ],
        //     [
        //         'category_id' => 5,
        //         'name' => 'Beef Steak',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 55000,
        //         'is_available' => true,
        //         'description' => 'Steak daging sapi dengan saus lada hitam.'
        //     ],

        //     // =========================
        //     // 🍔 BURGER (6)
        //     // =========================
        //     [
        //         'category_id' => 6,
        //         'name' => 'Classic Beef Burger',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 30000,
        //         'is_available' => true,
        //         'description' => 'Burger daging sapi dengan keju dan saus spesial.'
        //     ],

        //     // =========================
        //     // 🍗 FRIED CHICKEN (7)
        //     // =========================
        //     [
        //         'category_id' => 7,
        //         'name' => 'Ayam Crispy',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 24000,
        //         'is_available' => true,
        //         'description' => 'Ayam goreng crispy renyah dan gurih.'
        //     ],

        //     // =========================
        //     // 🍟 SNACK (8)
        //     // =========================
        //     [
        //         'category_id' => 8,
        //         'name' => 'Kentang Goreng',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 18000,
        //         'is_available' => true,
        //         'description' => 'Kentang goreng renyah disajikan dengan saus.'
        //     ],

        //     // =========================
        //     // 🍰 DESSERT (10)
        //     // =========================
        //     [
        //         'category_id' => 10,
        //         'name' => 'Chocolate Lava Cake',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 26000,
        //         'is_available' => true,
        //         'description' => 'Cake coklat dengan lelehan coklat hangat.'
        //     ],

        //     // =========================
        //     // 🍦 ICE CREAM (13)
        //     // =========================
        //     [
        //         'category_id' => 13,
        //         'name' => 'Vanilla Ice Cream',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 15000,
        //         'is_available' => true,
        //         'description' => 'Es krim vanilla lembut dan manis.'
        //     ],

        //     // =========================
        //     // ☕ COFFEE (15)
        //     // =========================
        //     [
        //         'category_id' => 15,
        //         'name' => 'Cappuccino',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 22000,
        //         'is_available' => true,
        //         'description' => 'Kopi espresso dengan foam susu lembut.'
        //     ],

        //     // =========================
        //     // 🧃 NON COFFEE (16)
        //     // =========================
        //     [
        //         'category_id' => 16,
        //         'name' => 'Chocolate Milk',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 20000,
        //         'is_available' => true,
        //         'description' => 'Minuman susu coklat dingin yang manis.'
        //     ],

        //     // =========================
        //     // 🍳 BREAKFAST (21)
        //     // =========================
        //     [
        //         'category_id' => 21,
        //         'name' => 'Nasi Goreng Breakfast',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 23000,
        //         'is_available' => true,
        //         'description' => 'Nasi goreng telur cocok untuk sarapan.'
        //     ],

        //     // =========================
        //     // 🍜 NOODLES (3)
        //     // =========================
        //     [
        //         'category_id' => 3,
        //         'name' => 'Mie Goreng Seafood',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 27000,
        //         'is_available' => true,
        //         'description' => 'Mie goreng dengan udang dan cumi segar.'
        //     ],

        //     // =========================
        //     // 🍝 PASTA (4)
        //     // =========================
        //     [
        //         'category_id' => 4,
        //         'name' => 'Aglio Olio',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 30000,
        //         'is_available' => true,
        //         'description' => 'Pasta bawang putih dengan olive oil dan chili flakes.'
        //     ],

        //     // =========================
        //     // 🔥 GRILL (5)
        //     // =========================
        //     [
        //         'category_id' => 5,
        //         'name' => 'Grilled Salmon',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 65000,
        //         'is_available' => true,
        //         'description' => 'Salmon panggang dengan saus lemon butter.'
        //     ],

        //     // =========================
        //     // 🍔 BURGER (6)
        //     // =========================
        //     [
        //         'category_id' => 6,
        //         'name' => 'Chicken Burger',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 28000,
        //         'is_available' => true,
        //         'description' => 'Burger ayam crispy dengan saus mayo.'
        //     ],

        //     // =========================
        //     // 🍗 FRIED CHICKEN (7)
        //     // =========================
        //     [
        //         'category_id' => 7,
        //         'name' => 'Ayam Geprek',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 26000,
        //         'is_available' => true,
        //         'description' => 'Ayam crispy dengan sambal pedas.'
        //     ],

        //     // =========================
        //     // 🍟 SNACK (8)
        //     // =========================
        //     [
        //         'category_id' => 8,
        //         'name' => 'Onion Rings',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 17000,
        //         'is_available' => true,
        //         'description' => 'Cincin bawang goreng renyah.'
        //     ],

        //     // =========================
        //     // 🍰 DESSERT (10)
        //     // =========================
        //     [
        //         'category_id' => 10,
        //         'name' => 'Cheesecake',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 28000,
        //         'is_available' => true,
        //         'description' => 'Cake keju lembut dengan topping strawberry.'
        //     ],

        //     // =========================
        //     // 🍦 ICE CREAM (13)
        //     // =========================
        //     [
        //         'category_id' => 13,
        //         'name' => 'Chocolate Ice Cream',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 15000,
        //         'is_available' => true,
        //         'description' => 'Es krim coklat creamy.'
        //     ],

        //     // =========================
        //     // ☕ COFFEE (15)
        //     // =========================
        //     [
        //         'category_id' => 15,
        //         'name' => 'Latte',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 24000,
        //         'is_available' => true,
        //         'description' => 'Kopi susu dengan espresso pilihan.'
        //     ],

        //     // =========================
        //     // 🧃 NON COFFEE (16)
        //     // =========================
        //     [
        //         'category_id' => 16,
        //         'name' => 'Matcha Latte',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 25000,
        //         'is_available' => true,
        //         'description' => 'Minuman matcha creamy dan segar.'
        //     ],

        //     // =========================
        //     // 🍳 BREAKFAST (21)
        //     // =========================
        //     [
        //         'category_id' => 21,
        //         'name' => 'Roti Bakar Telur',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 18000,
        //         'is_available' => true,
        //         'description' => 'Roti bakar isi telur dan keju.'
        //     ],

        //     // =========================
        //     // 🍳 LIGHT MEAL (22)
        //     // =========================
        //     [
        //         'category_id' => 22,
        //         'name' => 'Caesar Salad',
        //         'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
        //         'price' => 26000,
        //         'is_available' => true,
        //         'description' => 'Salad segar dengan dressing caesar.'
        //     ],
        // ];

        // foreach ($menus as $menu) {
        //     Menu::create($menu);
        // }


        $now = Carbon::now();

        // ===============================
        // GET CATEGORY IDS
        // ===============================
        $starter      = DB::table('categories')->where('name', 'Starter Menu')->value('id');
        $nusantara    = DB::table('categories')->where('name', 'Nusantara Delights')->value('id');
        $mainCourse   = DB::table('categories')->where('name', 'Main Course')->value('id');
        $riceBowl     = DB::table('categories')->where('name', 'Rice Bowl')->value('id');
        $sweet        = DB::table('categories')->where('name', 'Sweet & Pastries')->value('id');

        $coffeeEnthusiasm = DB::table('categories')->where('name', 'Coffee Enthusiasm')->value('id');
        $coffeeMocktail   = DB::table('categories')->where('name', 'Coffee Mocktail')->value('id');
        $manualBrew       = DB::table('categories')->where('name', 'Manual Brew')->value('id');
        $coffeeFusion     = DB::table('categories')->where('name', 'Coffee Fusion')->value('id');


        $choco        = DB::table('categories')->where('name', 'Choco')->value('id');
        $taro         = DB::table('categories')->where('name', 'Taro')->value('id');
        $yakult       = DB::table('categories')->where('name', 'Yakult')->value('id');
        $tea          = DB::table('categories')->where('name', 'Tea')->value('id');
        $slush        = DB::table('categories')->where('name', 'Slush')->value('id');
        // $artisanTea   = DB::table('categories')->where('name', 'Artisan Tea')->value('id');
        $summer       = DB::table('categories')->where('name', 'Summer')->value('id');
        $cosmopolitan = DB::table('categories')->where('name', 'Cosmopolitan')->value('id');
        $mocktail     = DB::table('categories')->where('name', 'Mocktail')->value('id');

        $matcha       = DB::table('categories')->where('name', 'Matcha Series')->value('id');



        // ===============================
        // STARTER MENU
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $starter,
                'name' => 'Spicy Corn Ribs',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 25000,
                'is_available' => true,
                'description' => 'Jagung goreng crispy dengan bumbu pedas.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Mix Platter',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Aneka camilan goreng dalam satu sajian.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Tahu Cabai Garam',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Tahu goreng dengan cabai dan bawang yang gurih.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Pisang Goreng',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Pisang goreng renyah dengan rasa manis alami.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Singkong Goreng',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Singkong goreng empuk dan gurih.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Spicy Wings',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Sayap ayam goreng dengan saus pedas.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Cireng',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 25000,
                'is_available' => true,
                'description' => 'Cireng goreng renyah di luar dan kenyal di dalam.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Tahu Walik',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 25000,
                'is_available' => true,
                'description' => 'Tahu walik goreng dengan isian gurih.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'French Fries',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 25000,
                'is_available' => true,
                'description' => 'Kentang goreng renyah disajikan hangat.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Spaghetti Bolognese',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 38000,
                'is_available' => true,
                'description' => 'Spaghetti dengan saus daging tomat khas Italia.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Spaghetti Carbonara',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 38000,
                'is_available' => true,
                'description' => 'Spaghetti saus krim dengan rasa gurih.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Spaghetti Aglio e Olio',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 40000,
                'is_available' => true,
                'description' => 'Spaghetti dengan bawang putih dan minyak zaitun.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Dimsum Original',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Dimsum ayam lembut dengan rasa original.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Dimsum Mentai',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Dimsum dengan saus mentai yang creamy.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $starter,
                'name' => 'Dimsum Mozzarella',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Dimsum dengan isian keju mozzarella lumer.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        // ===============================
        // NUSANTARA DELIGHT
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $nusantara,
                'name' => 'Crispy Duck Bali / Matah',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 50000,
                'is_available' => true,
                'description' => 'Bebek crispy dengan sambal matah khas Bali.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $nusantara,
                'name' => 'Pindang Patin Palembang',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 48000,
                'is_available' => true,
                'description' => 'Pindang patin khas Palembang yang segar dan gurih.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $nusantara,
                'name' => 'Pindang Iga Palembang',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 60000,
                'is_available' => true,
                'description' => 'Pindang iga sapi khas Palembang.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $nusantara,
                'name' => 'Tekwan',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Sup tekwan ikan khas Palembang.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $nusantara,
                'name' => 'Pempek Palembang',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Pempek Palembang dengan cuko khas.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $nusantara,
                'name' => 'Ayam Goreng Kalasan',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 40000,
                'is_available' => true,
                'description' => 'Ayam goreng kalasan dengan bumbu manis gurih.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $nusantara,
                'name' => 'Sate Pedas Selera',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Sate dengan bumbu pedas khas selera nusantara.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $nusantara,
                'name' => 'Sate Maranggi',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 48000,
                'is_available' => true,
                'description' => 'Sate maranggi sapi dengan bumbu khas.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);


        // ===============================
        // MAIN COURSE
        // ===============================

        DB::table('menus')->insert([
            [
                'category_id' => $mainCourse,
                'name' => 'Iga Bakar',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 60000,
                'is_available' => true,
                'description' => 'Iga bakar empuk dengan bumbu spesial.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mainCourse,
                'name' => 'Sop Buntut',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 70000,
                'is_available' => true,
                'description' => 'Sop buntut hangat dan gurih.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mainCourse,
                'name' => 'Kwetiau Goreng',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Kwetiau goreng dengan bumbu khas.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mainCourse,
                'name' => 'Mie Goreng',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Mie goreng gurih dan lezat.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mainCourse,
                'name' => 'Nasi Goreng',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Nasi goreng spesial dengan topping pilihan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mainCourse,
                'name' => 'Pecak Nila',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 45000,
                'is_available' => true,
                'description' => 'Ikan nila dengan sambal pecak segar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mainCourse,
                'name' => 'Nila Bakar Bumbu Rujak',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 45000,
                'is_available' => true,
                'description' => 'Nila bakar dengan bumbu rujak khas.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mainCourse,
                'name' => 'Ayam Goreng Manis',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 45000,
                'is_available' => true,
                'description' => 'Ayam goreng dengan saus manis gurih.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mainCourse,
                'name' => 'Ayam Bakar Bumbu Rujak',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 45000,
                'is_available' => true,
                'description' => 'Ayam bakar dengan bumbu rujak pedas manis.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        // ===============================
        // RICE BOWL
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $riceBowl,
                'name' => 'Rice Bowl Ayam',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Rice bowl ayam pilihan sambal.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $riceBowl,
                'name' => 'Rice Bowl Taichan Ayam',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Rice bowl taichan ayam.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $riceBowl,
                'name' => 'Rice Bowl Dori',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Rice bowl dori.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $riceBowl,
                'name' => 'Rice Bowl Beef',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 38000,
                'is_available' => true,
                'description' => 'Rice bowl beef juicy.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);


        // ===============================
        // Sweet & Pastries
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $sweet,
                'name' => 'Butter Croissant',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 28000,
                'is_available' => true,
                'description' => 'Croissant mentega renyah di luar dan lembut di dalam.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $sweet,
                'name' => 'Pain au Choco',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Croissant cokelat dengan isian chocolate bar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $sweet,
                'name' => 'Cromboloni Choco Sprinkles',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Cromboloni cokelat dengan taburan sprinkles.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $sweet,
                'name' => 'Pain Suisse',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Pastry dengan isian custard dan cokelat.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $sweet,
                'name' => 'Cookies Red Velvet',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 20000,
                'is_available' => true,
                'description' => 'Cookies red velvet lembut dan manis.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $sweet,
                'name' => 'Cookies Original',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 18000,
                'is_available' => true,
                'description' => 'Cookies original klasik dengan rasa buttery.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $sweet,
                'name' => 'Keju Aroma',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Keju aroma goreng dengan kulit lumpia renyah.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $sweet,
                'name' => 'Cheese Cake',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Cheesecake lembut dengan rasa creamy.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $sweet,
                'name' => 'Ice Cream Scoop',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 20000,
                'is_available' => true,
                // 'description' => '3 scoops of ice cream with mango / caramel jam.',
                'description' => '3 sendok es krim dengan selai mangga/karamel.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);


        // ===============================
        // COFFEE ENTHUSIASM
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $coffeeEnthusiasm,
                'name' => 'Espresso',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 18000,
                'is_available' => true,
                'description' => 'Single shot espresso dengan karakter kopi yang kuat.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeEnthusiasm,
                'name' => 'Americano',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 26000,
                'is_available' => true,
                'description' => 'Espresso dengan tambahan air panas, ringan dan seimbang.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeEnthusiasm,
                'name' => 'Cafe Latte',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 28000,
                'is_available' => true,
                'description' => 'Espresso dengan susu steamed yang creamy dan lembut.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeEnthusiasm,
                'name' => 'Cappuccino',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 28000,
                'is_available' => true,
                'description' => 'Perpaduan espresso, susu steamed, dan foam susu seimbang.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeEnthusiasm,
                'name' => 'Magic',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Kopi susu hot khas dengan rasa bold dan creamy.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        // ===============================
        // COFFEE MOCKTAIL
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $coffeeMocktail,
                'name' => 'Montblanc',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Kombinasi kopi dengan rasa creamy dan sentuhan manis.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeMocktail,
                'name' => 'Black Lemonade',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Kopi hitam segar dengan perpaduan lemon yang menyegarkan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeMocktail,
                'name' => 'Black Oranje',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Kopi hitam dengan sentuhan rasa jeruk yang segar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        // ===============================
        // COFFEE MANUAL BREW
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $manualBrew,
                'name' => 'Manual Brew (Local)',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Kopi manual brew menggunakan biji kopi lokal pilihan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $manualBrew,
                'name' => 'Manual Brew (Seasonal)',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 33000,
                'is_available' => true,
                'description' => 'Manual brew dengan biji kopi seasonal pilihan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        // ===============================
        // COFFEE FUSION
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $coffeeFusion,
                'name' => 'Affogato',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 24000,
                'is_available' => true,
                'description' => 'Perpaduan espresso panas dengan es krim vanilla.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeFusion,
                'name' => 'Creme Brulee',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Kopi dengan rasa creamy caramel ala creme brulee.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeFusion,
                'name' => 'Butter Scotch',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Kopi dengan rasa manis gurih butterscotch.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeFusion,
                'name' => 'Gula Aren',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Kopi susu dengan manis alami gula aren.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeFusion,
                'name' => 'Spanish Latte OG',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Spanish latte original dengan rasa creamy dan manis seimbang.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $coffeeFusion,
                'name' => 'Spanish Latte Pistachio',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Spanish latte dengan sentuhan rasa pistachio.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);



        // ===============================
        // NON COFFEE - CHOCO
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $choco,
                'name' => 'Chocolate',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Minuman cokelat klasik dengan rasa rich dan creamy.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $choco,
                'name' => 'Chocolate Mint',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Perpaduan cokelat manis dengan sensasi mint yang segar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $choco,
                'name' => 'Chocolate Pistachio',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Cokelat creamy dengan sentuhan rasa pistachio.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);


        // ===============================
        // NON COFFEE - TARO
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $taro,
                'name' => 'Taro Latte',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Minuman taro creamy.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $taro,
                'name' => 'Taro Cheese',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Taro dengan foam cheese.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        // ===============================
        // NON COFFEE - YAKULT
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $yakult,
                'name' => 'Easy Peachy',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Minuman yakult dengan rasa peach yang segar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $yakult,
                'name' => 'Le Lact',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Perpaduan yakult dengan rasa creamy yang lembut.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $yakult,
                'name' => 'Sakura Bloom',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Minuman yakult dengan aroma floral yang ringan dan segar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        // ===============================
        // NON COFFEE - TEA
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $tea,
                'name' => 'Strawberry Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 20000,
                'is_available' => true,
                'description' => 'Teh segar dengan rasa stroberi yang manis dan ringan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $tea,
                'name' => 'Mango Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 20000,
                'is_available' => true,
                'description' => 'Perpaduan teh dengan rasa mangga yang segar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $tea,
                'name' => 'Peach Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 22000,
                'is_available' => true,
                'description' => 'Teh dengan aroma dan rasa peach yang lembut.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $tea,
                'name' => 'Lemon Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 22000,
                'is_available' => true,
                'description' => 'Teh segar dengan perpaduan rasa lemon yang menyegarkan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $tea,
                'name' => 'Jasmine Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 20000,
                'is_available' => true,
                'description' => 'Teh melati dengan aroma floral yang menenangkan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $tea,
                'name' => 'Lychee Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 22000,
                'is_available' => true,
                'description' => 'Teh dengan rasa leci yang manis dan segar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);



        // ===============================
        // NON COFFEE - SLUSH
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $slush,
                'name' => 'Sour Supreme Mango',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 25000,
                'is_available' => true,
                'description' => 'Minuman slush mangga dengan sensasi asam segar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $slush,
                'name' => 'Grapes Apple Berry',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 25000,
                'is_available' => true,
                'description' => 'Perpaduan slush anggur, apel, dan berry yang menyegarkan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $slush,
                'name' => 'Lychee Berry',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 25000,
                'is_available' => true,
                'description' => 'Slush leci dengan sentuhan rasa berry yang manis dan segar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $slush,
                'name' => 'Orange Peach',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 25000,
                'is_available' => true,
                'description' => 'Slush jeruk dan peach dengan rasa segar dan ringan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);


        // ===============================
        // NON COFFEE - MOCKTAIL
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $mocktail,
                'name' => 'Summer Flamingo',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 28000,
                'is_available' => true,
                'description' => 'Mocktail segar dengan rasa buah tropis yang menyenangkan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mocktail,
                'name' => 'Shining Yellow',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 28000,
                'is_available' => true,
                'description' => 'Mocktail citrus segar dengan warna cerah dan rasa ringan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mocktail,
                'name' => 'Beach Please',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Mocktail tropis dengan sensasi segar ala pantai.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $mocktail,
                'name' => 'Velvet Hibiscus',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Mocktail bunga hibiscus dengan rasa asam manis yang lembut.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);


        // ===============================
        // MATCHA SERIES
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $matcha,
                'name' => 'Matcha Original',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Matcha murni dengan rasa earthy yang khas dan seimbang.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $matcha,
                'name' => 'Matcha Creme Brulee',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 40000,
                'is_available' => true,
                'description' => 'Perpaduan matcha dengan creme brulee yang creamy dan manis.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $matcha,
                'name' => 'Dirty Matcha',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 40000,
                'is_available' => true,
                'description' => 'Matcha dengan tambahan espresso untuk rasa yang bold.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $matcha,
                'name' => 'Strawberry Matcha',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 40000,
                'is_available' => true,
                'description' => 'Matcha lembut dengan sentuhan manis stroberi.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $matcha,
                'name' => 'Matcha Pistachio',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 40000,
                'is_available' => true,
                'description' => 'Matcha creamy dengan rasa pistachio yang gurih.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $matcha,
                'name' => 'Matcha Cheese',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Matcha dengan foam keju yang creamy dan ringan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        // ===============================
        // ARTISAN TEA - SUMMER SERIES
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $summer,
                'name' => 'Hibiscus Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Teh hibiscus dengan rasa asam segar dan aroma bunga.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $summer,
                'name' => 'Chamomile Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Teh chamomile dengan aroma lembut yang menenangkan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $summer,
                'name' => 'Apple Bits Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Infused tea dengan potongan apel yang segar dan manis.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        // ===============================
        // ARTISAN TEA - COSMOPOLITAN SERIES
        // ===============================
        DB::table('menus')->insert([
            [
                'category_id' => $cosmopolitan,
                'name' => 'Cranberry Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Infused tea dengan rasa cranberry yang segar dan asam manis.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $cosmopolitan,
                'name' => 'Goji Berry Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Teh dengan goji berry yang kaya rasa dan menyegarkan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $cosmopolitan,
                'name' => 'Apple Bits Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Infused tea dengan potongan apel yang manis dan segar.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => $cosmopolitan,
                'name' => 'Mint Leaves Tea',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Teh dengan daun mint yang menyegarkan dan ringan.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
