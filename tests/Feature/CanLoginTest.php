<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CanLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanShowLogin()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function testCanSendLogin()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password1234'),
            'email'    => 'example@example.org',
            'name'     => 'John Doe',
        ]);

        // Create room with post
        $response = $this->from('/login')->post('/login', [
            'email'      => 'example@example.org',
            'password'    => 'password1234',
        ]);

        // Redirect back
        $response->assertStatus(302);
        $response->assertRedirect('/rooms/edit');

        // Assert no errors, everything should work fine now
        $response->assertSessionHasNoErrors();

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);
    }

    // Test email and password fields are required
    public function testEmptyPostIsRejected()
    {
        // Get the page
        $response = $this->from('/login')->post('/login');

        // Redirect back with data about the error
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        // Assert errors since fields are empty
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors('password');
    }

    // Test wrong username/password combination does not work
    public function testWrongLoginNotWorks()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password1234'),
            'email'    => 'example@example.org',
            'name'     => 'John Doe',
        ]);

        // Create room with post
        $response = $this->from('/login')->post('/login', [
            'email'      => 'example@example.org',
            'password'    => 'not-the-password',
        ]);

        // Redirect back
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        // Error: These credentials do not match our records.
        $response->assertSessionHasErrors();

        $this->assertGuest();
    }
}
