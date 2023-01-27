<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ProgramTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_program_index()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $a = 1;

        $this->assertEquals(1, $a);
        $response = $this->get('/api/v1/programs');

        $response->assertStatus(200);
        $response->assertOk();
    }
}
