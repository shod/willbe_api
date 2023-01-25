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
            'name' => fake()->randomElement(['Health membership', 'Start changing your life']),
            'description' => fake()->randomElement(['{
                "1": "Justo nostrud vel sea sit magna lorem amet dolore consetetur at sed erat gubergren ullamcorper.",
                "2": "Sit takimata cum et sit",
                "3": "Sadipscing et doming. Sed facilisis takimata voluptua ea nisl justo eirmod nulla consetetur volutpat voluptua et et magna nulla stet stet aliquya"
            }']),
        ];
    }
}
