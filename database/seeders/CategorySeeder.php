<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::create(['title' => 'Electronics', 'route' => 'electronics', 'position' => 1]);
        $clothing = Category::create(['title' => 'Clothing', 'route' => 'clothing', 'position' => 2]);

        Category::create(['title' => 'Phones', 'route' => 'phones', 'parent_id' => $electronics->id, 'position' => 1]);
        Category::create(['title' => 'Laptops', 'route' => 'laptops', 'parent_id' => $electronics->id, 'position' => 2]);
        Category::create(['title' => 'Men', 'route' => 'men', 'parent_id' => $clothing->id, 'position' => 1]);
    }
}
