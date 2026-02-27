<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
        // $categories = [

        //     // 🍽️ MAIN DISH
        //     ['name' => 'Main Course'],
        //     ['name' => 'Rice Bowl'],
        //     ['name' => 'Noodles'],
        //     ['name' => 'Pasta'],
        //     ['name' => 'Grill'],

        //     // 🍔 FAST FOOD
        //     ['name' => 'Burger'],
        //     ['name' => 'Fried Chicken'],
        //     ['name' => 'Snack'],
        //     ['name' => 'Side Dish'],

        //     // 🍰 DESSERT
        //     ['name' => 'Dessert'],
        //     ['name' => 'Cake'],
        //     ['name' => 'Pastry'],
        //     ['name' => 'Ice Cream'],
        //     ['name' => 'Pudding'],

        //     // ☕ DRINKS
        //     ['name' => 'Coffee'],
        //     ['name' => 'Non Coffee'],
        //     ['name' => 'Tea'],
        //     ['name' => 'Juice'],
        //     ['name' => 'Mocktail'],
        //     ['name' => 'Milk Based'],

        //     // 🍳 BREAKFAST
        //     ['name' => 'Breakfast'],
        //     ['name' => 'Light Meal'],

        // ];

        // foreach ($categories as $category) {
        //     DB::table('categories')->insert([
        //         'name' => $category['name'],

        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        $now = Carbon::now();

        // ===============================
        // PARENT CATEGORIES
        // ===============================
        $starter = DB::table('categories')->insertGetId([
            'name' => 'Starter Menu',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $nusantara = DB::table('categories')->insertGetId([
            'name' => 'Nusantara Delights',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $mainCourse = DB::table('categories')->insertGetId([
            'name' => 'Main Course',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $riceBowl = DB::table('categories')->insertGetId([
            'name' => 'Rice Bowl',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $sweet = DB::table('categories')->insertGetId([
            'name' => 'Sweet & Pastries',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $coffee = DB::table('categories')->insertGetId([
            'name' => 'Coffee',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $nonCoffee = DB::table('categories')->insertGetId([
            'name' => 'Non-Coffee',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $matcha = DB::table('categories')->insertGetId([
            'name' => 'Matcha Series',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ===============================
        // CHILD CATEGORIES - COFFEE
        // ===============================
        $coffeeChildren = [
            'Coffee Enthusiasm',
            'Coffee Mocktail',
            'Manual Brew',
            'Coffee Fusion',
        ];

        foreach ($coffeeChildren as $name) {
            DB::table('categories')->insert([
                'name' => $name,
                'parent_id' => $coffee,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ===============================
        // CHILD CATEGORIES - NON COFFEE
        // ===============================
        $nonCoffeeChildren = [
            'Choco',
            'Taro',
            'Yakult',
            'Tea',
            'Slush',
            'Artisan Tea',
            'Mocktail',
        ];

        foreach ($nonCoffeeChildren as $name) {
            DB::table('categories')->insert([
                'name' => $name,
                'parent_id' => $nonCoffee,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $artisanTeaChild = [
            'Summer',
            'Cosmopolitan',
        ];

        foreach ($artisanTeaChild as $name) {
            DB::table('categories')->insert([
                'name' => $name,
                'parent_id' => 18,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
