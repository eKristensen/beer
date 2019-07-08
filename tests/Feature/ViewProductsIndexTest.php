<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ViewProductsIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPageLoadsUnauthorized()
    {
        $response = $this->get('/products');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }


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
        $response = $this->get('/products');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that the page loads fine
        $response->assertStatus(200);
    }

    public function testProductShows() {
        // create sample product
        $product = Product::create([
            'name' => 'Test product',
            'color' => 'fff000',
            'quantity' => '1,2,5',
            'price' => '1232.00',
        ]);

        // Create a user so that we can see that product
        $user = User::create([
            'password' => Hash::make('password'),
            'email' => 'example@example.org',
            'name' => 'John Doe',
        ]);

        // Login that user in
        Auth::login($user);

        // Get the page
        $response = $this->get('/products');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that the page loads fine
        $response->assertStatus(200);

        // And that we see the product:
        $response->assertSee($product->quantity);
        $response->assertSee($product->price);
        $response->assertSee($product->name);
        $response->assertSee($product->color);
    }
}
