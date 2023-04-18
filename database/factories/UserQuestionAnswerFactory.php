<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserQuestionAnswer>
 */
class UserQuestionAnswerFactory extends Factory
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
            'question_id' => fake()->unique()->numberBetween(10, 15),
            'point' => fake()->numberBetween(0, 3),
        ];
    }
}
