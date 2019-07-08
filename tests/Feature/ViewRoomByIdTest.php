<?php

namespace Tests\Feature;

use App\Beer;
use App\Product;
use App\Room;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ViewRoomByIdTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRoomDoesNotExistUnauthorized()
    {
        $response = $this->get('/rooms/1');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    // Test room does not exists authorized
    public function testRoomDoesNotExistAuthorized()
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
        $response = $this->get('/rooms/1');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that we get the expected 404
        $response->assertStatus(404);
    }

    public function testPageLoadsWithRoomUnauthorized()
    {
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        $response = $this->get('/rooms/1');

        // Redirect is expected since we're not logged in
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    // Test room does exists authorized
    public function testPageLoadsWithRoomAuthorized()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email' => 'example@example.org',
            'name' => 'John Doe',
        ]);

        // Create room
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        // Login that user in
        Auth::login($user);

        // Get the page
        $response = $this->get('/rooms/1');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that we get the expected 200 OK
        $response->assertStatus(200);
    }

    // Test room data is shown
    public function testRoomDataIsShown()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email' => 'example@example.org',
            'name' => 'John Doe',
        ]);

        // Create room
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        // Login that user in
        Auth::login($user);

        // Get the page
        $response = $this->get('/rooms/1');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that we get the expected 200 OK
        $response->assertStatus(200);

        // And that the room data is shown
        $response->assertSee("Transtrationer for konto id: ".$room->id);
    }

    // test that beers purchased is there
    public function testPurchasesIsShown()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email' => 'example@example.org',
            'name' => 'John Doe',
        ]);

        // Create room
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        // Create sample product
        $product = Product::create([
            'name' => 'Test product',
            'color' => 'fff',
            'quantity' => '1,2,5',
            'price' => '1232.00',
        ]);

        $quantity = 2;

        // Buy something
        $beer = new Beer();
        $beer->room = $room->id;
        $beer->quantity = $quantity;
        $beer->product = $product->id;
        $beer->ipAddress = request()->ip();
        $beer->amount = -($product->price * $quantity);
        $beer->save();

        // Login that user in
        Auth::login($user);

        // Get the page
        $response = $this->get('/rooms/1');

        // Asser user is logged in
        $this->assertAuthenticatedAs($user);

        // And that we get the expected 200 OK
        $response->assertStatus(200);

        // And the purchase shows up, we only check if the price is shown
        $response->assertSee("-".($product->price*2).".00");
    }
}
