<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [

            // =========================
            // 🍽️ MAIN COURSE (1)
            // =========================
            [
                'category_id' => 1,
                'name' => 'Nasi Kebuli Ayam',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 34000,
                'is_available' => true,
                'description' => 'Nasi kebuli harum dengan ayam berbumbu rempah khas Timur Tengah.'
            ],
            [
                'category_id' => 1,
                'name' => 'Nasi Goreng Spesial',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 28000,
                'is_available' => true,
                'description' => 'Nasi goreng dengan telur, ayam, dan kerupuk.'
            ],

            // =========================
            // 🍚 RICE BOWL (2)
            // =========================
            [
                'category_id' => 2,
                'name' => 'Rice Bowl Ayam Teriyaki',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 25000,
                'is_available' => true,
                'description' => 'Ayam teriyaki manis gurih disajikan dengan nasi hangat.'
            ],
            [
                'category_id' => 2,
                'name' => 'Rice Bowl Beef Blackpepper',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 29000,
                'is_available' => true,
                'description' => 'Daging sapi empuk dengan saus lada hitam.'
            ],

            // =========================
            // 🍜 NOODLES (3)
            // =========================
            [
                'category_id' => 3,
                'name' => 'Mie Goreng Jawa',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 22000,
                'is_available' => true,
                'description' => 'Mie goreng khas Jawa dengan cita rasa manis gurih.'
            ],
            [
                'category_id' => 3,
                'name' => 'Mie Kuah Ayam',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 20000,
                'is_available' => true,
                'description' => 'Mie kuah hangat dengan ayam dan sayuran.'
            ],

            // =========================
            // 🍝 PASTA (4)
            // =========================
            [
                'category_id' => 4,
                'name' => 'Spaghetti Bolognese',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 32000,
                'is_available' => true,
                'description' => 'Spaghetti dengan saus daging tomat klasik.'
            ],
            [
                'category_id' => 4,
                'name' => 'Fettuccine Carbonara',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 35000,
                'is_available' => true,
                'description' => 'Pasta creamy dengan saus carbonara dan smoked beef.'
            ],

            // =========================
            // 🔥 GRILL (5)
            // =========================
            [
                'category_id' => 5,
                'name' => 'Chicken Steak',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 38000,
                'is_available' => true,
                'description' => 'Dada ayam panggang dengan saus barbeque.'
            ],
            [
                'category_id' => 5,
                'name' => 'Beef Steak',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 55000,
                'is_available' => true,
                'description' => 'Steak daging sapi dengan saus lada hitam.'
            ],

            // =========================
            // 🍔 BURGER (6)
            // =========================
            [
                'category_id' => 6,
                'name' => 'Classic Beef Burger',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Burger daging sapi dengan keju dan saus spesial.'
            ],

            // =========================
            // 🍗 FRIED CHICKEN (7)
            // =========================
            [
                'category_id' => 7,
                'name' => 'Ayam Crispy',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 24000,
                'is_available' => true,
                'description' => 'Ayam goreng crispy renyah dan gurih.'
            ],

            // =========================
            // 🍟 SNACK (8)
            // =========================
            [
                'category_id' => 8,
                'name' => 'Kentang Goreng',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 18000,
                'is_available' => true,
                'description' => 'Kentang goreng renyah disajikan dengan saus.'
            ],

            // =========================
            // 🍰 DESSERT (10)
            // =========================
            [
                'category_id' => 10,
                'name' => 'Chocolate Lava Cake',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 26000,
                'is_available' => true,
                'description' => 'Cake coklat dengan lelehan coklat hangat.'
            ],

            // =========================
            // 🍦 ICE CREAM (13)
            // =========================
            [
                'category_id' => 13,
                'name' => 'Vanilla Ice Cream',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 15000,
                'is_available' => true,
                'description' => 'Es krim vanilla lembut dan manis.'
            ],

            // =========================
            // ☕ COFFEE (15)
            // =========================
            [
                'category_id' => 15,
                'name' => 'Cappuccino',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 22000,
                'is_available' => true,
                'description' => 'Kopi espresso dengan foam susu lembut.'
            ],

            // =========================
            // 🧃 NON COFFEE (16)
            // =========================
            [
                'category_id' => 16,
                'name' => 'Chocolate Milk',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 20000,
                'is_available' => true,
                'description' => 'Minuman susu coklat dingin yang manis.'
            ],

            // =========================
            // 🍳 BREAKFAST (21)
            // =========================
            [
                'category_id' => 21,
                'name' => 'Nasi Goreng Breakfast',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 23000,
                'is_available' => true,
                'description' => 'Nasi goreng telur cocok untuk sarapan.'
            ],

            // =========================
            // 🍜 NOODLES (3)
            // =========================
            [
                'category_id' => 3,
                'name' => 'Mie Goreng Seafood',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 27000,
                'is_available' => true,
                'description' => 'Mie goreng dengan udang dan cumi segar.'
            ],

            // =========================
            // 🍝 PASTA (4)
            // =========================
            [
                'category_id' => 4,
                'name' => 'Aglio Olio',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 30000,
                'is_available' => true,
                'description' => 'Pasta bawang putih dengan olive oil dan chili flakes.'
            ],

            // =========================
            // 🔥 GRILL (5)
            // =========================
            [
                'category_id' => 5,
                'name' => 'Grilled Salmon',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 65000,
                'is_available' => true,
                'description' => 'Salmon panggang dengan saus lemon butter.'
            ],

            // =========================
            // 🍔 BURGER (6)
            // =========================
            [
                'category_id' => 6,
                'name' => 'Chicken Burger',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 28000,
                'is_available' => true,
                'description' => 'Burger ayam crispy dengan saus mayo.'
            ],

            // =========================
            // 🍗 FRIED CHICKEN (7)
            // =========================
            [
                'category_id' => 7,
                'name' => 'Ayam Geprek',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 26000,
                'is_available' => true,
                'description' => 'Ayam crispy dengan sambal pedas.'
            ],

            // =========================
            // 🍟 SNACK (8)
            // =========================
            [
                'category_id' => 8,
                'name' => 'Onion Rings',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 17000,
                'is_available' => true,
                'description' => 'Cincin bawang goreng renyah.'
            ],

            // =========================
            // 🍰 DESSERT (10)
            // =========================
            [
                'category_id' => 10,
                'name' => 'Cheesecake',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 28000,
                'is_available' => true,
                'description' => 'Cake keju lembut dengan topping strawberry.'
            ],

            // =========================
            // 🍦 ICE CREAM (13)
            // =========================
            [
                'category_id' => 13,
                'name' => 'Chocolate Ice Cream',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 15000,
                'is_available' => true,
                'description' => 'Es krim coklat creamy.'
            ],

            // =========================
            // ☕ COFFEE (15)
            // =========================
            [
                'category_id' => 15,
                'name' => 'Latte',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 24000,
                'is_available' => true,
                'description' => 'Kopi susu dengan espresso pilihan.'
            ],

            // =========================
            // 🧃 NON COFFEE (16)
            // =========================
            [
                'category_id' => 16,
                'name' => 'Matcha Latte',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 25000,
                'is_available' => true,
                'description' => 'Minuman matcha creamy dan segar.'
            ],

            // =========================
            // 🍳 BREAKFAST (21)
            // =========================
            [
                'category_id' => 21,
                'name' => 'Roti Bakar Telur',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 18000,
                'is_available' => true,
                'description' => 'Roti bakar isi telur dan keju.'
            ],

            // =========================
            // 🍳 LIGHT MEAL (22)
            // =========================
            [
                'category_id' => 22,
                'name' => 'Caesar Salad',
                'image' => 'menus/oWgnVhXBQqc62Hrks2sXotAeZE7udLrxrZz6zrBX.jpg',
                'price' => 26000,
                'is_available' => true,
                'description' => 'Salad segar dengan dressing caesar.'
            ],


        ];



        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
