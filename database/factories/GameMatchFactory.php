<?php

namespace Database\Factories;

use App\Models\GameMatch;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameMatchFactory extends Factory
{
    protected $model = GameMatch::class;

    public function definition(): array
    {
        return [
            'match_name' => fake()->words(3, true),
            'match_date' => fake()->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d'),
            'match_time' => fake()->time('H:i:s'),
            'location' => fake()->city(),
            'description' => fake()->optional()->sentence(),
        ];
    }
}