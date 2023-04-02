<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Target>
 */
class TargetFactory extends Factory
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
            'name' => fake()->realText(24, 1),
            'description' => fake()->paragraph(5),
            'status' => fake()->randomElement(['todo', 'inprogress', 'done'])
        ];
    }
}
