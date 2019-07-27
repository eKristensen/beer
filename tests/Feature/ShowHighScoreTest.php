<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowHighScoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPageLoads()
    {
        $response = $this->get('/highscore');

        $response->assertStatus(200);
    }

    // API tested below, GUI tested in vue with vue unit testing
}
