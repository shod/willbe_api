<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestUser>
 */
class UserTestFactory extends Factory
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
            'test_id' => fake()->numberBetween(1, 5),
            'program_id' => fake()->numberBetween(1, 2),
            'status' => fake()->randomElement(['todo', 'inprogress', 'done']),
            'attach_files' => '[]'
        ];
    }
}
