<?php

namespace Tests\Feature;

use App\Beer;
use App\Product;
use App\Room;
use Tests\TestCase;

class ApiBuyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Test room not found
    public function testRoomNotFound()
    {
        $response = $this->get('/api/buy/1/1/1');

        // Expect a code 404
        $response->assertStatus(404);
    }

    // test product not found
    public function testProductNotFound()
    {
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $response = $this->get('/api/buy/'.$room->id.'/1/1');

        // Expect a code 404
        $response->assertStatus(404);
    }

    // Check that quanity will always be an integer
    public function testQuantityIsAlwyasAnInteger()
    {
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

        $notNumbers = [
            'a',
            '1a',
            -1, // Test negative quantities does not allow
        ];

        foreach ($notNumbers as $test) {
            // Get sum now
            $sum_before = Room::find($room->id)->sum;

            $response = $this->get('/api/buy/'.$room->id.'/'.$product->id.'/'.$test);

            // Expect a code 404 Not Found
            $response->assertStatus(404);

            // Check the sum of the room is unchanged
            $sum_after = Room::find($room->id)->sum;

            // Sum before and after must be the same, otherwise a purchase went on anyways, not good
            $this->assertEquals($sum_before, $sum_after);
        }
    }

    // Test only active rooms can buy stuff
    // Expect 403: error: Room inactive
    public function testRoomInactive()
    {
        $room = Room::create([
            'id'     => 1,
            'name'   => 'Test room',
            'active' => false,
        ]);

        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        // Get sum now
        $sum_before = Room::find($room->id)->sum;

        // Sum now should be zero
        $this->assertEquals($sum_before, 0);

        // Try to buy one of whatever
        $response = $this->get('/api/buy/'.$room->id.'/'.$product->id.'/1');

        // Expect a code 403 Access Denied
        $response->assertStatus(403);

        // Assert proper JSON response
        $response->assertJson([
            'error' => 'Room inactive',
        ]);

        // Check the sum of the room is unchanged
        $sum_after = Room::find($room->id)->sum;

        // Sum before and after must be the same, otherwise a purchase went on anyways, not good
        $this->assertEquals($sum_before, $sum_after);
    }

    // Test only active products can be purchased
    // Expect 403: error: Product inactive
    public function testProductInactive()
    {
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
            'active'   => false,
        ]);

        // Get sum now
        $sum_before = Room::find($room->id)->sum;

        // Sum now should be zero
        $this->assertEquals($sum_before, 0);

        // Try to buy one of whatever
        $response = $this->get('/api/buy/'.$room->id.'/'.$product->id.'/1');

        // Expect a code 403 Access Denied
        $response->assertStatus(403);

        // Assert proper JSON response
        $response->assertJson([
            'error' => 'Product inactive',
        ]);

        // Check the sum of the room is unchanged
        $sum_after = Room::find($room->id)->sum;

        // Sum before and after must be the same, otherwise a purchase went on anyways, not good
        $this->assertEquals($sum_before, $sum_after);
    }

    // test purchase works for a product, and sum is updated
    public function testBuyWorks()
    {
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

        // Get sum now
        $sum_before = Room::find($room->id)->sum;

        // Sum now should be zero
        $this->assertEquals($sum_before, 0);

        // Try to buy one of whatever
        $response = $this->get('/api/buy/'.$room->id.'/'.$product->id.'/1');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'name'    => $room->name,
            'product' => $product->name,
            'sum'     => -$product->price,
        ]);

        // Check the sum of the room is unchanged
        $sum_after = Room::find($room->id)->sum;

        // Sum must have changed with the price of the product, otherwise the purchase didn't work.
        $this->assertEquals($sum_before - $product->price, $sum_after);

        // Test IP shows up as expected and is saved,
        // asumed to be the only purchase in the database
        $this->assertEquals(Beer::first()->ipAddress, request()->ip());

        // Checks that there is only one purchase in the database
        $this->assertEquals(Beer::count(), 1);
    }
}
