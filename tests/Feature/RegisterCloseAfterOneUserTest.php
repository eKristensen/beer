<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterCloseAfterOneUserTest extends TestCase
{
    // https://laravel.com/docs/5.8/database-testing#using-migrations
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWithNoUser()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function testWithUserRegistered()
    {
        $user = User::create([
            'password' => Hash::make('password'),
            'email' => 'example@example.org',
            'name' => 'John Doe',
        ]);

        $response = $this->get('/register');

        $response->assertStatus(302);
    }
}
