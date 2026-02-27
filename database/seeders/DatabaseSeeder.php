<?php

namespace Database\Seeders;

// use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        // Category::create(['name' => 'Makanan']);
        // Category::create(['name' => 'Minuman']);

        $this->call([TableSeeder::class]);
        $this->call([CategorySeeder::class]);
        $this->call([MenuSeeder::class]);
        $this->call([OptionGroupSeeder::class]);
        $this->call([OptionSeeder::class]);
        $this->call([MenuOptionGroupSeeder::class]);
        $this->call([OrderSeeder::class]);
        $this->call([OrderItemSeeder::class]);
        $this->call([TaxSeeder::class]);
        $this->call([PriceOfferSeeder::class]);
    }
}
