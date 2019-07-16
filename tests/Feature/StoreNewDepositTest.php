<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StoreNewDepositTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Test login is required
    public function testLoginIsRequired()
    {
        $response = $this->post('/deposit');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    // Test fields are required
    //        'room'   => 'required',
    //        'amount' => 'required|numeric|gt:0',
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
        $response = $this->from('/deposit')->post('/deposit');

        // Redirect back with data about the error
        $response->assertStatus(302);
        $response->assertRedirect('/deposit');

        // Assert errors since fields are empty
        $response->assertSessionHasErrors('room');
        $response->assertSessionHasErrors('amount');
    }

    // Test amount is numeric, and positive, and not a string
    public function testAmountMustBeNumber()
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
            'a'    => true,
            -1     => true,
            0      => true,
            0.01   => false,
            2      => false,
            2.89   => false,
            30     => false,
            30.33  => false,
        ];

        foreach ($idToTest as $key => $value) {
            // Get the page
            $response = $this->from('/deposit')->post('/deposit', [
                'amount' => $key,
            ]);

            // Redirect back with data about the error
            $response->assertStatus(302);
            $response->assertRedirect('/deposit');

            // Assert error on id field depending on the test
            if ($value) {
                $response->assertSessionHasErrors('amount');
            }

            // Assert errors since fields are empty
            $response->assertSessionHasErrors('room');
        }
    }

    // Test deposit is stored
    public function testDepositIsWorking()
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
        $this->assertDatabaseMissing('beers', [
            'room'     => 1,
            'amount'    => 2.45,
        ]);

        // Create room with post
        $response = $this->from('/deposit')->post('/deposit', [
            'room'     => 1,
            'amount'    => 2.45,
        ]);

        // Redirect back
        $response->assertStatus(302);
        $response->assertRedirect('/deposit');

        // Assert no errors, everything should work fine now
        $response->assertSessionHasNoErrors();

        // Check the newly created room is stored
        $this->assertDatabaseHas('beers', [
            'room'     => 1,
            'amount'    => 2.45,
            'product' => 'deposit',
            'quantity' => 1,
            'ipAddress' => request()->ip(),
        ]);
    }
}
