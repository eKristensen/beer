<?php

namespace Tests\Feature;

use App\Room;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PatchRoomTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Test login is required
    public function testLoginIsRequired()
    {
        $response = $this->patch('/rooms');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    // Test validation rules
    //        'id'     => 'required',
    //        'name'   => 'nullable',
    //        'active' => 'nullable',
    public function testEmptyPatchIsRejected()
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
        $response = $this->from('/rooms')->patch('/rooms');

        // Redirect back with data about the error
        $response->assertStatus(302);
        $response->assertRedirect('/rooms');

        // Assert errors since fields are empty
        $response->assertSessionHasErrors('id');
    }

    // Test patch success with:
    // id only
    // id and name
    // id and active
    // id, name and active
    // Each test needs: input, output, expected success/failure
    public function testPatchRoomWorks()
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
            [ // Test #1, set active to false implicit
                'setup' => [
                    'id'   => 1,
                    'name' => 'Test room',
                ],
                'input' => [
                    'id' => 1,
                ],
                'output' => [
                    'id' => 1,
                    'name' => 'Test room',
                    'active' => 0,
                ]
            ],
            [ // Test #2 set active to false implicit
                'setup' => [
                    'id'   => 1,
                    'name' => 'Test room',
                ],
                'input' => [
                    'id' => 1,
                    'name' => 'Test room changed',
                ],
                'output' => [
                    'id' => 1,
                    'name' => 'Test room changed',
                    'active' => 0,
                ]
            ],
            [ // Test #3, change active test, start with room being inactive
                'setup' => [
                    'id'   => 1,
                    'name' => 'Test room',
                    'active' => false,
                ],
                'input' => [
                    'id' => 1,
                    'active' => 1,
                ],
                'output' => [
                    'id' => 1,
                    'name' => 'Test room',
                    'active' => 1,
                ]
            ],
            [ // Test #4
                'setup' => [
                    'id'   => 1,
                    'name' => 'Test room',
                    'active' => false,
                ],
                'input' => [
                    'id' => 1,
                    'name' => 'Test room changed',
                    'active' => 1,
                ],
                'output' => [
                    'id' => 1,
                    'name' => 'Test room changed',
                    'active' => 1,
                ]
            ],
        ];

        foreach ($idToTest as $value) {
            // Create room
            $room = Room::create($value['setup']);

            // Get the page
            $response = $this->from('/rooms')->patch('/rooms', $value['input']);

            // Redirect back with data about the error
            $response->assertStatus(302);
            $response->assertRedirect('/rooms');

            // Assert no errors, everything should work fine now
            $response->assertSessionHasNoErrors();

            $this->assertDatabaseHas('rooms', $value['output']);

            // Delete test room
            Room::find(1)->delete();
        }
    }

    // Test active fails if value is not 1, or not there
    public function testPatchActiveFailsIfNotValidValue()
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
            0  => true,
            true => true,
            false => true,
            '0' => true,
            'a' => true,
            '1' => false,
            1  => false,
            null => false,
        ];

        foreach ($idToTest as $key => $value) {
            // Get the page
            $response = $this->from('/rooms')->patch('/rooms', [
                'active' => $key
            ]);

            // Redirect back with data about the error
            $response->assertStatus(302);
            $response->assertRedirect('/rooms');

            // Assert error on id field depending on the test
            if ($value) {
                $response->assertSessionHasErrors('active');
            }

            // Assert errors since fields are empty
            $response->assertSessionHasErrors('id');
        }
    }
}
