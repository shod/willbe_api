<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UserSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_sessions')->insert([
            'user_id' => 1001,
            'session_id' => 1,
            'status' => 'done',
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime()
        ]);
        DB::table('user_sessions')->insert([
            'user_id' => 1001,
            'session_id' => 2,
            'status' => 'inprogress',
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime()
        ]);
        DB::table('user_sessions')->insert([
            'user_id' => 1001,
            'session_id' => 3,
            'status' => 'next',
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime()
        ]);
    }
}
