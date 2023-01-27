<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserStep>
 */
class UserStepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 1004,
            'session_step_id' => fake()->unique()->numberBetween(1, 5),
            'status_bit' => fake()->numberBetween(0, 4),
        ];
    }
}
