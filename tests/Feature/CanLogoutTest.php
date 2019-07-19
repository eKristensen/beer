<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CanLogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanLogout()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email'    => 'example@example.org',
            'name'     => 'John Doe',
        ]);

        // Login that user in
        Auth::login($user);

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // Get the login page
        $response = $this->post('/logout');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);

        $this->assertGuest();
    }

    public function testLogoutWhileLoggedOut()
    {
        // Get the login page
        $response = $this->post('/logout');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);

        $this->assertGuest();
    }
}
