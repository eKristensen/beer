<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StoreNewProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // Test login is required
    public function testLoginIsRequired()
    {
        $response = $this->post('/products');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }


    // Test fields are required, with data as seams nessassary
    //        'name'     => 'required',
    //        'color'    => 'nullable',
    //        'quantity' => 'required',
    //        'price'    => 'required',
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
        $response = $this->from('/products')->post('/products');

        // Redirect back with data about the error
        $response->assertStatus(302);
        $response->assertRedirect('/products');

        // Assert errors since fields are empty
        $response->assertSessionHasErrors('name');
        $response->assertSessionHasErrors('quantity');
        $response->assertSessionHasErrors('price');
    }

    // Test price must be doubble
    public function testPriceMustBeANumber()
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
            0.01   => false,
            2   => false,
            2.89   => false,
            30  => false,
            30.33  => false,
        ];

        foreach ($idToTest as $key => $value) {
            // Get the page
            $response = $this->from('/products')->post('/products', [
                'price' => $key,
            ]);

            // Redirect back with data about the error
            $response->assertStatus(302);
            $response->assertRedirect('/products');

            // Assert error on id field depending on the test
            if ($value) {
                $response->assertSessionHasErrors('price');
            }

            // Assert errors since fields are empty
            $response->assertSessionHasErrors('name');
            $response->assertSessionHasErrors('quantity');
        }
    }

    // Test success creation without color
    public function testProductIsCreatedWithoutColor()
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
        $this->assertDatabaseMissing('products', [
            'name' => 'Test Product',
            'quantity' => '1,2,5',
            'price' => 2.45,
        ]);

        // Create room with post
        $response = $this->from('/products')->post('/products', [
            'name' => 'Test Product',
            'quantity' => '1,2,5',
            'price' => 2.45,
        ]);

        // Redirect back
        $response->assertStatus(302);
        $response->assertRedirect('/products');

        // Assert no errors, everything should work fine now
        $response->assertSessionHasNoErrors();

        // Check the newly created room is stored
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'quantity' => '1,2,5',
            'color' => null,
            'price' => 2.45,
        ]);
    }

    // Test success creation with color
    public function testProductIsCreatedWithColor()
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
        $this->assertDatabaseMissing('products', [
            'name' => 'Test Product',
            'quantity' => '1,2,5',
            'price' => 2.45,
            'color' => 'f0a0e0',
        ]);

        // Create room with post
        $response = $this->from('/products')->post('/products', [
            'name' => 'Test Product',
            'quantity' => '1,2,5',
            'price' => 2.45,
            'color' => 'f0a0e0',
        ]);

        // Redirect back
        $response->assertStatus(302);
        $response->assertRedirect('/products');

        // Assert no errors, everything should work fine now
        $response->assertSessionHasNoErrors();

        // Check the newly created room is stored
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'quantity' => '1,2,5',
            'price' => 2.45,
            'color' => 'f0a0e0',
        ]);
    }
}
