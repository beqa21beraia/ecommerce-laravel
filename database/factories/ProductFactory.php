<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 10, 500);

        return [
            'title' => $this->faker->words(3, true),
            'meta_keys' => $this->faker->words(5, true),
            'meta_description' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(3, true),
            'is_visible' => true,
            'is_hot' => $this->faker->boolean(20),
            'is_new' => $this->faker->boolean(30),
            'price' => $price,
            'sale_price' => null,
            'sale_start_date' => null,
            'sale_end_date' => null,
            'barcode' => $this->faker->ean13(),
            'amount_in_stock' => $this->faker->numberBetween(0, 200),
            'route' => $this->faker->unique()->slug(),
            'position' => $this->faker->numberBetween(1, 100),
        ];
    }

    public function onSale(): static
    {
        return $this->state(fn () => [
            'sale_price' => $this->faker->randomFloat(2, 5, 400),
            'sale_start_date' => now()->subDay(),
            'sale_end_date' => now()->addWeek(),
        ]);
    }
}
