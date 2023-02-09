<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserStep;
use DB;

class UserStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //UserStep::factory()->times(5)->create();
        DB::table('user_steps')->insert([
            'user_id' => 1001,
            'session_step_id' => 1,
            'status_bit' => 4
        ]);
        DB::table('user_steps')->insert([
            'user_id' => 1001,
            'session_step_id' => 2,
            'status_bit' => 2
        ]);
        DB::table('user_steps')->insert([
            'user_id' => 1001,
            'session_step_id' => 3,
            'status_bit' => 1
        ]);
    }
}
