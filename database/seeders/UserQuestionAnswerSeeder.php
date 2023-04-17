<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserQuestionAnswer;
use DB;

class UserQuestionAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = DB::table('user_question_answers');

        $table->truncate();

        $table->insert([
            'user_id'     => 1004,
            'question_id' => 1,
            'point'  => 0,
        ]);

        UserQuestionAnswer::factory()->times(10)->create();
    }
}
