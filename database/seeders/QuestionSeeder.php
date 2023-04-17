<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = DB::table('questions');

        $table->truncate();

        $table->insert([
            'id' => 1,
            'name' => 'Willbe.’s Health Questionnaire',
        ]);

        $table->insert([
            'id' => 2,
            'name' => 'Part 1',
            'parent_id' => 1,
        ]);

        $table->insert([
            'id' => 3,
            'name' => 'Part 2',
            'parent_id' => 1,
        ]);

        Question::factory()->times(20)->create();

        $this->result();
    }

    private function result()
    {
        $this->call(QuestionResultSeeder::class);
        $this->call(UserQuestionAnswerSeeder::class);
    }
}
