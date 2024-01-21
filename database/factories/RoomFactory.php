<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'number' => $this->faker->unique()->numberBetween(100, 999),
            'type' => $this->faker->randomElement(['single', 'double', 'suite']),
            'price_per_night' => $this->faker->randomFloat(2, 50, 300),
            'status' => $this->faker->randomElement(['available', 'occupied'])
        ];
    }
}
