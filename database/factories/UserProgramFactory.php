<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserProgram;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserProgramFactory extends Factory
{
    protected $model = UserProgram::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'program_id' => 1,
            'user_id' => fake(2)->unique()->numberBetween(1000, 1001),
            'status_bit' => 0
        ];
    }
}
