<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StoreNewRoomTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Test login is required
    public function testLoginIsRequired()
    {
        $response = $this->post('/rooms');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    // Test that the validation works.
    //        'id' => 'required',
    //        'name' => 'required'
    public function testEmptyPostIsRejected()
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

        // Get the page
        $response = $this->from('/rooms/edit')->post('/rooms');

        // Redirect back with data about the error
        $response->assertStatus(302);
        $response->assertRedirect('/rooms/edit');

        // Assert errors since fields are empty
        $response->assertSessionHasErrors('id');
        $response->assertSessionHasErrors('name');
    }

    // Test id must be int
    public function testIdMustBeAnInteger()
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

        // Test different id's:
        // true = error is expected, false error is not expected
        $idToTest = [
            'a' => true,
            -1  => true,
            0   => true,
            2   => false,
            30  => false,
        ];

        foreach ($idToTest as $key => $value) {
            // Get the page
            $response = $this->from('/rooms/edit')->post('/rooms', [
                'id' => $key,
            ]);

            // Redirect back with data about the error
            $response->assertStatus(302);
            $response->assertRedirect('/rooms/edit');

            // Assert error on id field depending on the test
            if ($value) {
                $response->assertSessionHasErrors('id');
            }

            // Assert errors since fields are empty
            $response->assertSessionHasErrors('name');
        }
    }

    // Test room is created with name
    public function testRoomIsCreated()
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

        // Check that room 22 does not exist prior to the test
        $this->assertDatabaseMissing('rooms', [
            'id' => 22,
        ]);

        // Create room with post
        $response = $this->from('/rooms/edit')->post('/rooms', [
            'id'   => 22,
            'name' => 'Test room',
        ]);

        // Redirect back
        $response->assertStatus(302);
        $response->assertRedirect('/rooms/edit');

        // Assert no errors, everything should work fine now
        $response->assertSessionHasNoErrors();

        // Check the newly created room is stored
        $this->assertDatabaseHas('rooms', [
            'id'   => 22,
            'name' => 'Test room',
        ]);
    }
}
