<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake(2)->randomElement(['Health membership', 'Trial Health coaching session']),
            'description' => fake(2)->randomElement(['Start changing your life', 'Discover how it can change your life']),
        ];
    }
}
