<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class QuestionResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = DB::table('question_results');

        $table->truncate();

        $table->insert([
            'question_id' => 1,
            'min_points'  => 50,
            'description' => 'Willbe.’s Health Questionnaire Result 50',
        ]);

        $table->insert([
            'question_id' => 1,
            'min_points'  => 100,
            'description' => 'Willbe.’s Health Questionnaire Result 100',
        ]);

        $table->insert([
            'question_id' => 1,
            'min_points'  => 140,
            'description' => 'Willbe.’s Health Questionnaire Result 140',
        ]);
    }
}
