<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterCloseAfterOneUserTest extends TestCase
{
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
            'email'    => 'example@example.org',
            'name'     => 'John Doe',
        ]);

        $response = $this->get('/register');

        $response->assertStatus(302);
    }
}
