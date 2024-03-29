<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(ProgramsSeeder::class);
        $this->call(UserProgramsSeeder::class);
        $this->call(SessionsSeeder::class);
        $this->call(SessionStepSeeder::class);
        $this->call(UserStepSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSessionSeeder::class);
        $this->call(ConsultationSeeder::class);
        $this->call(TargetSeeder::class);
        $this->call(TestSeeder::class);
        $this->call(UserTestsSeeder::class);
        $this->call(FileSeeder::class);
        $this->call(QuestionSeeder::class);
    }
}
