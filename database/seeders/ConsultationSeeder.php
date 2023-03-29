<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = DB::table('consultations');

        $table->insert([
            'coach_id' => 1001,
            'client_id' => 1002,
            'status_bit' => 0,
            'meet_time' => (new \DateTimeImmutable())->getTimestamp()
        ]);
        $table->insert([
            'coach_id' => 1001,
            'client_id' => 1003,
            'status_bit' => 0,
            'meet_time' => (new \DateTimeImmutable())->getTimestamp()
        ]);
        $table->insert([
            'client_id' => 1002,
            'status_bit' => 0
        ]);
    }
}
