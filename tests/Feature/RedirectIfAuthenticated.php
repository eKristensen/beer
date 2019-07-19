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

    // Test middleware RedirectIfAuthenticated works
    public function testRedirectIfAuthenticatedMiddleware()
    {
        // Insperation: https://semaphoreci.com/community/tutorials/testing-middleware-in-laravel-with-phpunit

        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email'    => 'example@example.org',
            'name'     => 'John Doe',
        ]);

        $this->actingAs($user);

        $request = Request::create('/login', 'GET');

        $middleware = new RedirectIfAuthenticated;

        // Use the middleware
        $response = $middleware->handle($request, function () {});

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/rooms/edit');
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
