<?php

namespace Tests\Feature;

use App\Beer;
use App\Product;
use App\Room;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ViewDepositTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // Test login required
    public function testUnauthorizedRedirect()
    {
        $response = $this->get('/deposit');

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
            'email'    => 'example@example.org',
            'name'     => 'John Doe',
        ]);

        // Login that user in
        Auth::login($user);

        // Get the page
        $response = $this->get('/deposit');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that we get the expected 200 OK
        $response->assertStatus(200);
    }

    // Test room data shows, includeing dept and total dept for every room
    public function testRoomDataShows()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email'    => 'example@example.org',
            'name'     => 'John Doe',
        ]);

        // Login that user in
        Auth::login($user);

        // Create room
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        // Get the page
        $response = $this->get('/deposit');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that we get the expected 200 OK
        $response->assertStatus(200);

        // No purchases means the sum is expected to be zero
        $this->assertEquals($room->sum, 0);

        // And that the room data is shown
        $response->assertSee('<option value="'.$room->id.'">'.$room->id
            .' '.$room->name.' '.$room->sum.'</option>', $escaped = false);
        $response->assertSee('<p>Total difference '.$room->sum.'</p>', $escaped = false);
    }

    // Test room with purchase, total sum must show (more than one purchase)
    public function testRoomDataShowsForRoomWithPurchases()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email'    => 'example@example.org',
            'name'     => 'John Doe',
        ]);

        // Login that user in
        Auth::login($user);

        // Create room
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $quantity = 2;

        $beer = new Beer();
        $beer->room = $room->id;
        $beer->quantity = $quantity;
        $beer->product = $product->id;
        $beer->ipAddress = request()->ip();
        $beer->amount = -($product->price * $quantity);
        $beer->save();

        // Get the page
        $response = $this->get('/deposit');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that we get the expected 200 OK
        $response->assertStatus(200);

        // No purchases means the sum is expected to be -1232*2
        $this->assertEquals($room->sum, -$quantity * $product->price);

        // And that the room data is shown
        $response->assertSee('<option value="'.$room->id.'">'.$room->id
            .' '.$room->name.' '.$room->sum.'</option>', $escaped = false);
        $response->assertSee('<p>Total difference '.$room->sum.'</p>', $escaped = false);
    }

    // Test total difference with more than one room with serveral purchases

    // Test room with purchase, total sum must show (more than one purchase)
    public function testRoomDataShowsForServeralRoomsWithPurchases()
    {
        // Create a user
        $user = User::create([
            'password' => Hash::make('password'),
            'email'    => 'example@example.org',
            'name'     => 'John Doe',
        ]);

        // Login that user in
        Auth::login($user);

        // Create room
        $room_1 = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $room_2 = Room::create([
            'id'   => 2,
            'name' => 'Test room',
        ]);

        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $quantity = 2;

        $beer_1 = new Beer();
        $beer_1->room = $room_1->id;
        $beer_1->quantity = $quantity;
        $beer_1->product = $product->id;
        $beer_1->ipAddress = request()->ip();
        $beer_1->amount = -($product->price * $quantity);
        $beer_1->save();

        $beer_2 = new Beer();
        $beer_2->room = $room_2->id;
        $beer_2->quantity = $quantity;
        $beer_2->product = $product->id;
        $beer_2->ipAddress = request()->ip();
        $beer_2->amount = -($product->price * $quantity * 2);
        $beer_2->save();

        // Get the page
        $response = $this->get('/deposit');

        // Asser user is logged in now
        $this->assertAuthenticatedAs($user);

        // And that we get the expected 200 OK
        $response->assertStatus(200);

        // No purchases means the sum is expected to be -1232*2
        // Room two got twice as much
        $this->assertEquals($room_1->sum, -$quantity * $product->price);
        $this->assertEquals($room_2->sum, -$quantity * $product->price * 2);

        // And that the room data is shown
        $response->assertSee('<option value="'.$room_1->id.'">'.$room_1->id
            .' '.$room_1->name.' '.$room_1->sum.'</option>', $escaped = false);
        $response->assertSee('<option value="'.$room_2->id.'">'.$room_2->id
            .' '.$room_2->name.' '.$room_2->sum.'</option>', $escaped = false);
        $response->assertSee('<p>Total difference '.($room_1->sum + $room_2->sum).'.00</p>', $escaped = false);
    }
}
