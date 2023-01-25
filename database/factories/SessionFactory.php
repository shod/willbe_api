<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'program_id' => 1,
            'num' => fake()->unique()->numberBetween(1, 8),
            'name' => fake()->realText(24, 1),
            'description' => fake()->paragraph(5),
        ];
    }
}
