<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PatchProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Test login is required
    public function testLoginIsRequired()
    {
        $response = $this->patch('/products');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    // Test validation rules, empty patch request
    //        'id'       => 'required',
    //        'name'     => 'required',
    //        'color'    => 'required',
    //        'quantity' => 'required',
    //        'price'    => 'required|numeric|gt:0',
    //        'active'   => 'nullable|in:1',
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
        $response = $this->from('/products')->patch('/products');

        // Redirect back with data about the error
        $response->assertStatus(302);
        $response->assertRedirect('/products');

        // Assert errors since fields are empty
        $response->assertSessionHasErrors('id');
        $response->assertSessionHasErrors('name');
        $response->assertSessionHasErrors('color');
        $response->assertSessionHasErrors('quantity');
        $response->assertSessionHasErrors('price');
    }

    // Test price must be numeric and greather than 0
    public function testPriceMustBePorsitiveNumber()
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
            $response = $this->from('/products')->patch('/products', [
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
            $response->assertSessionHasErrors('id');
            $response->assertSessionHasErrors('name');
            $response->assertSessionHasErrors('color');
            $response->assertSessionHasErrors('quantity');
        }
    }

    // Test patch works
    public function testPatchProductWorks()
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
                    'name'     => 'Test product',
                    'price'    => 2,
                    'color'    => 'fff',
                    'quantity' => '1,2,5',
                    'active'   => 1,
                ],
                'input' => [
                    'name'     => 'Test product changed',
                    'price'    => 7.77,
                    'color'    => '0f0f',
                    'quantity' => '5,8,9',
                ],
                'output' => [
                    'name'     => 'Test product changed',
                    'price'    => 7.77,
                    'color'    => '0f0f',
                    'quantity' => '5,8,9',
                    'active'   => 0,
                ],
            ],
            [ // Test #1, set active to true explicit
                'setup' => [
                    'name'     => 'Test product',
                    'price'    => 2,
                    'color'    => 'fff',
                    'quantity' => '1,2,5',
                    'active'   => 0,
                ],
                'input' => [
                    'name'     => 'Test product changed',
                    'price'    => 7.77,
                    'color'    => '0f0f',
                    'quantity' => '5,8,9',
                    'active'   => 'on',
                ],
                'output' => [
                    'name'     => 'Test product changed',
                    'price'    => 7.77,
                    'color'    => '0f0f',
                    'quantity' => '5,8,9',
                    'active'   => 1,
                ],
            ],
        ];

        foreach ($idToTest as $value) {
            // Create product to patch test
            $product = Product::create($value['setup']);

            // Add id to product, input and output
            $value['input'] = array_merge($value['input'], ['id' => $product->id]);
            $value['output'] = array_merge($value['output'], ['id' => $product->id]);

            // Get the page
            $response = $this
                ->from('/products')
                ->patch('/products', $value['input']);

            // Redirect back with data about the error
            $response->assertStatus(302);
            $response->assertRedirect('/products');

            // Assert no errors, everything should work fine now
            $response->assertSessionHasNoErrors();

            $this->assertDatabaseHas('products', $value['output']);
        }
    }
}
