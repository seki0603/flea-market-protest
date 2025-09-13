<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            UserProfilesTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductsTableSeeder::class,
        ]);

        $products = Product::all();
        $categories = Category::all();

        foreach ($products as $product) {
            $randomCategories = $categories->random(rand(1, 3))->pluck('id')->toArray();
            $product->categories()->syncWithoutDetaching($randomCategories);
        }
    }
}
