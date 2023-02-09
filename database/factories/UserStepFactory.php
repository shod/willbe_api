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
            'user_id' => 1001,
            'session_step_id' => fake(1)->unique()->numberBetween(1, 5),
            'status_bit' => fake(1)->numberBetween(0, 4),
        ];
    }
}
