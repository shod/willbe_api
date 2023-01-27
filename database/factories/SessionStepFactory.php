<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SessionStep>
 */
class SessionStepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'session_id' => 1,
            'name' => fake()->realText(24, 1),
            'num' => fake()->unique()->numberBetween(1, 5),
        ];
    }
}
