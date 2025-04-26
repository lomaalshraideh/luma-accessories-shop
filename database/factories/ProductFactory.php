<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        $name = $this->faker->words(3, true);

    return [
        'category_id' => \App\Models\Category::factory(),
        'name' => ucfirst($name),
        'slug' => Str::slug($name) . '-' . Str::random(5),
        'description' => $this->faker->paragraph(),
        'price' => $this->faker->randomFloat(2, 5, 500),
        'stock' => $this->faker->numberBetween(0, 100),
        'audience' => $this->faker->randomElement(['children', 'elderly', 'special_needs']),
    ];
    }
}
