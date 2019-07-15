<?php

namespace Tests\Feature;

use App\Beer;
use App\Product;
use App\Room;
use Tests\TestCase;

class ApiRefundPurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Test page loads
    public function testPageLoads()
    {
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

        $response = $this->get('/api/refund/'.$beer->id);

        $response->assertStatus(200);
    }

    // Test proper 404
    public function testNotFound()
    {
        $response = $this->get('/api/refund/1');

        $response->assertStatus(404);
    }

    // test refund of purchaseg inside 30 minutes is accepted
    public function testRefundWorks()
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

        $quantity = 2;

        // Buy two test product
        $beer = Beer::create([
            'room'      => $room->id,
            'quantity'  => $quantity,
            'product'   => $product->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product->price * $quantity),
        ]);

        // Get fresh beer from the database
        $beer = Beer::find($beer->id);

        // Refunded must be false = 0
        $this->assertEquals($beer->refunded, 0);

        $response = $this->get('/api/refund/'.$beer->id);

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                'refunded' => true,
                'amount'   => $beer->amount,
            ],
        ]);

        // Get fresh beer from the database
        $beer = Beer::find($beer->id);

        // Refunded must be true = 1
        $this->assertEquals($beer->refunded, 1);
    }

    // Test refund of already refunded product
    // Still OK.
    public function testRefundOkWhenAlreadyRefunded()
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

        $quantity = 2;

        // Buy two test product
        $beer = Beer::create([
            'room'      => $room->id,
            'quantity'  => $quantity,
            'product'   => $product->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product->price * $quantity),
        ]);

        // Get fresh beer from the database and refund it right away
        $beer = Beer::find($beer->id);
        $beer->refund;

        // Refresh beer again
        $beer = Beer::find($beer->id);

        // Refunded must be true = 1
        $this->assertEquals($beer->refunded, 1);

        $response = $this->get('/api/refund/'.$beer->id);

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                'refunded' => true,
                'amount'   => $beer->amount,
            ],
        ]);

        // Get fresh beer from the database
        $beer = Beer::find($beer->id);

        // Refunded must be true = 1
        $this->assertEquals($beer->refunded, 1);
    }

    // Test refund outside the 30 minutes is blocked
    // Expect 403 Access denied
    public function testRefundOutsideTimeframeIsRejected()
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

        $quantity = 2;

        // Buy two test product
        $beer = Beer::create([
            'room'      => $room->id,
            'quantity'  => $quantity,
            'product'   => $product->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product->price * $quantity),
        ]);

        // Set time to be 35 minutes behind in time for all purchases
        \DB::update(
            'UPDATE beers SET created_at = ?',
            [\Carbon\Carbon::now()->subMinutes(35)->toDateTimeString()]
        );

        // Get fresh beer from the database
        $beer = Beer::find($beer->id);

        // Refunded must be false = 0
        $this->assertEquals($beer->refunded, 0);

        $response = $this->get('/api/refund/'.$beer->id);

        // Expect a code 403 Access denied
        $response->assertStatus(403);

        // Assert proper JSON response
        $response->assertJson([
            'error' => 'Refund is not possible',
        ]);

        // Get fresh beer from the database
        $beer = Beer::find($beer->id);

        // Refunded must be still be false = 0
        $this->assertEquals($beer->refunded, 0);
    }
}
