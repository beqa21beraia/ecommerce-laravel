<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::whereNotNull('parent_id')->get();

        Product::factory()
            ->count(30)
            ->create()
            ->each(function (Product $product) use ($categories) {
                $product->categories()->attach(
                    $categories->random(rand(1, 2))->pluck('id')
                );
            });

        Product::factory()
            ->count(5)
            ->onSale()
            ->create()
            ->each(function (Product $product) use ($categories) {
                $product->categories()->attach($categories->random()->id);
            });
    }
}
