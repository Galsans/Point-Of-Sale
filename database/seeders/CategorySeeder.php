<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [

            // 🍽️ MAIN DISH
            ['name' => 'Main Course'],
            ['name' => 'Rice Bowl'],
            ['name' => 'Noodles'],
            ['name' => 'Pasta'],
            ['name' => 'Grill'],

            // 🍔 FAST FOOD
            ['name' => 'Burger'],
            ['name' => 'Fried Chicken'],
            ['name' => 'Snack'],
            ['name' => 'Side Dish'],

            // 🍰 DESSERT
            ['name' => 'Dessert'],
            ['name' => 'Cake'],
            ['name' => 'Pastry'],
            ['name' => 'Ice Cream'],
            ['name' => 'Pudding'],

            // ☕ DRINKS
            ['name' => 'Coffee'],
            ['name' => 'Non Coffee'],
            ['name' => 'Tea'],
            ['name' => 'Juice'],
            ['name' => 'Mocktail'],
            ['name' => 'Milk Based'],

            // 🍳 BREAKFAST
            ['name' => 'Breakfast'],
            ['name' => 'Light Meal'],

        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
