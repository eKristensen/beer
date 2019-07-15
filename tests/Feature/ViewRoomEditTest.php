<?php

namespace Tests\Feature;

use App\Room;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ViewRoomEditTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Test redirect if not logged in
    public function testUnauthorizedRedirect()
    {
        $response = $this->get('/rooms/edit');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    // Test page loads authorized
    public function testPageLoadsAuthorized()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email' => 'example@example.org',
            'name' => 'John Doe',
        ]);

        // Login that user in
        Auth::login($user);

        // Get the page
        $response = $this->get('/rooms/edit');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that we get the expected 200 OK
        $response->assertStatus(200);
    }

    // Test room data shows
    public function testRoomDataShows()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email' => 'example@example.org',
            'name' => 'John Doe',
        ]);

        // Login that user in
        Auth::login($user);

        // Create room
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        // Get the page
        $response = $this->get('/rooms/edit');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that we get the expected 200 OK
        $response->assertStatus(200);

        // And that the room data is shown
        $response->assertSee('<th scope="row">' . $room->id . '</th>');
        $response->assertSee('<input type="text" class="form-control" '
            . 'id="name" name="name" placeholder="Name" value="' . $room->name . '">');
    }
}
