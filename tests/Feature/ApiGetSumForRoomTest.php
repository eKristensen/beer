<?php

namespace Tests\Feature;

use App\Beer;
use App\Product;
use App\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiGetSumForRoomTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // API route loads with room
    public function testApiRouteLoads()
    {
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        $response = $this->get('/api/sum/'.$room->id);

        // Expect a code 200 OK
        $response->assertStatus(200);
    }

    // Proper 404 for non existing room
    public function testNotFound()
    {
        $response = $this->get('/api/sum/1');

        // Expect a code 404
        $response->assertStatus(404);
    }

    // Room with a purchase shows right sum
    public function testSumWithSinglePurchase()
    {
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        $product = Product::create([
            'name' => 'Test product',
            'color' => 'fff',
            'quantity' => '1,2,5',
            'price' => '1232.00',
        ]);

        $quantity = 2;

        $beer = new Beer();
        $beer->room = $room->id;
        $beer->quantity = $quantity;
        $beer->product = $product->id;
        $beer->ipAddress = request()->ip();
        $beer->amount = -($product->price * $quantity);
        $beer->save();

        $response = $this->get('/api/sum/'.$room->id);

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Check that the sum is as expected
        $this->assertEquals($room->sum, -$quantity*$product->price);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                'name' => $room->name,
                'sum' => $room->sum,
            ],
        ]);
    }

    // Room with two purchases shows right sum
    public function testSumWithTwoPurchases()
    {
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        $product = Product::create([
            'name' => 'Test product',
            'color' => 'fff',
            'quantity' => '1,2,5',
            'price' => '1232.00',
        ]);

        $quantity = 2;

        $beer_1 = new Beer();
        $beer_1->room = $room->id;
        $beer_1->quantity = $quantity;
        $beer_1->product = $product->id;
        $beer_1->ipAddress = request()->ip();
        $beer_1->amount = -($product->price * $quantity);
        $beer_1->save();


        $beer_2 = new Beer();
        $beer_2->room = $room->id;
        $beer_2->quantity = $quantity;
        $beer_2->product = $product->id;
        $beer_2->ipAddress = request()->ip();
        $beer_2->amount = -($product->price * $quantity * 2);
        $beer_2->save();

        $response = $this->get('/api/sum/'.$room->id);

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Check that the sum is as expected
        $this->assertEquals($room->sum, -$quantity*$product->price-$product->price*$quantity*2);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                'name' => $room->name,
                'sum' => $room->sum,
            ],
        ]);
    }

    // Room with a purchase and deposit shows right sum
    public function testSumWithPurchaseAndDeposit()
    {
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        $product = Product::create([
            'name' => 'Test product',
            'color' => 'fff',
            'quantity' => '1,2,5',
            'price' => '1232.00',
        ]);

        $quantity = 2;

        // Buy two test product
        $beer = new Beer();
        $beer->room = $room->id;
        $beer->quantity = $quantity;
        $beer->product = $product->id;
        $beer->ipAddress = request()->ip();
        $beer->amount = -($product->price * $quantity);
        $beer->save();

        // Despsit 100
        $deposit = Beer::create([
            'room' => $room->id,
            'amount' => 100,
            'type' => 'deposit',
            'quantity' => 1,
            'ipAddress' => request()->ip(),
        ]);

        $response = $this->get('/api/sum/'.$room->id);

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Check that the sum is as expected
        $this->assertEquals($room->sum, -$quantity*$product->price+$deposit->amount);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                'name' => $room->name,
                'sum' => $room->sum,
            ],
        ]);
    }

    // Room with refunded purchase shows right
    public function testSumWithRefundedPurchase()
    {
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        $product = Product::create([
            'name' => 'Test product',
            'color' => 'fff',
            'quantity' => '1,2,5',
            'price' => '1232.00',
        ]);

        $quantity = 2;

        // Buy two test product
        $beer = new Beer();
        $beer->room = $room->id;
        $beer->quantity = $quantity;
        $beer->product = $product->id;
        $beer->ipAddress = request()->ip();
        $beer->amount = -($product->price * $quantity);
        $beer->refunded = true;
        $beer->save();

        $response = $this->get('/api/sum/'.$room->id);

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Check that the sum is as expected, with only a refunded purchase that would be zero.
        $this->assertEquals($room->sum, 0);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                'name' => $room->name,
                'sum' => $room->sum,
            ],
        ]);
    }

    // Check that the sum is only for one room, not serveral rooms
    public function testSumIsOnlyForTheRequestedRoom()
    {
        // Create room
        $room_1 = Room::create([
            'id' => 1,
            'name' => 'Test room'
        ]);

        $room_2 = Room::create([
            'id' => 2,
            'name' => 'Test room'
        ]);

        $product = Product::create([
            'name' => 'Test product',
            'color' => 'fff',
            'quantity' => '1,2,5',
            'price' => '1232.00',
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

        $response = $this->get('/api/sum/'.$room_1->id);

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Check that the sum is as expected, with only a refunded purchase that would be zero.
        $this->assertEquals($room_1->sum, -$product->price * $quantity);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                'name' => $room_1->name,
                'sum' => $room_1->sum,
            ],
        ]);
    }
}
