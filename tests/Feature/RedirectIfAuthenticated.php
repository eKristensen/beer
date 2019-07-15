<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RedirectIfAuthenticated extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNotAuthenticatedYet()
    {
        $response = $this->get('/login');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    // redirect if you try to visit the login page while logged in
    public function testNotAuthenticated()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email'    => 'example@example.org',
            'name'     => 'John Doe',
        ]);

        // Login that user in
        Auth::login($user);

        // Get the login page
        $response = $this->get('/login');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // Redirect is expected since we're logged in
        $response->assertStatus(302);
        $response->assertRedirect('/rooms/edit');
    }
}
