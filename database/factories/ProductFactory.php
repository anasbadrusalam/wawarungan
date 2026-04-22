<?php

namespace Database\Factories;

use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($this->faker));
        $this->faker->locale('id_ID');

        return [
            'type' => $this->faker->randomElement(ProductType::cases())->value,

            'name' => $this->faker->productName(),

            'code' => Str::upper(Str::random(8)),

            'cost' => $this->faker->randomFloat(2, 1000, 50000),
            'price' => $this->faker->randomFloat(2, 50000, 150000),

            'manage_stock' => true,

            'category_id' => null, // nanti bisa di-relasikan
            'brand_id' => null,
        ];
    }

    public function goods()
    {
        return $this->state(fn() => [
            'type' => ProductType::Goods->value,
            'manage_stock' => true,
        ]);
    }

    public function service()
    {
        return $this->state(fn() => [
            'type' => ProductType::Service->value,
            'manage_stock' => false,
        ]);
    }
}
