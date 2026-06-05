<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'nombre'      => $this->faker->words(3, true),
            'precio'      => $this->faker->randomFloat(2, 10, 500),
            'stock'       => $this->faker->numberBetween(1, 100),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}