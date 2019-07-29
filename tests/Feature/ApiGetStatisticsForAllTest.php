<?php

namespace Tests\Feature;

use App\Beer;
use App\Product;
use App\Room;
use Tests\TestCase;

class ApiGetStatisticsForAllTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRouteLoads()
    {
        $response = $this->get('/api/statistics');

        $response->assertStatus(200);
    }

    public function testCanLoaddata()
    {
        // Sample data
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

        $response = $this->get('/api/statistics');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    $room->name,
                    $beer->quantity,
                ],
            ],
        ]);

        // Check that the count is not text
        $response->assertDontSee('"'.$beer->quantity.'"');
    }

    // Kun for aktive produkter
    public function testInactiveProductIsExcluded()
    {
        // Sample data
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

        $quantity = 2;

        // Buy two test product
        $beer = Beer::create([
            'room'      => $room->id,
            'quantity'  => $quantity,
            'product'   => $product->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product->price * $quantity),
        ]);

        $response = $this->get('/api/statistics');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    $room->name,
                    0,
                ],
            ],
        ]);
    }

    // Kun ting der ikke er refundret
    public function testDontCountRefunded()
    {
        // Sample data
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

        // Refunder
        $beer->refund;

        $response = $this->get('/api/statistics');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    $room->name,
                    0,
                ],
            ],
        ]);
    }

    // Kun aktive værelser
    public function testDontShowInactiveRooms()
    {
        // Sample data
        $room = Room::create([
            'id'     => 1,
            'name'   => 'Test room',
            'active' => false,
        ]);

        $response = $this->get('/api/statistics');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertDontSee($room->name);
    }

    // Kun for 30 dage
    public function testOldTransactionsAreExcluded()
    {
        // Sample data
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

        // Set time to be 31 days behind in time for all purchases
        \DB::update(
            'UPDATE beers SET created_at = ?',
            [\Carbon\Carbon::now()->subDays(31)->toDateTimeString()]
        );

        $response = $this->get('/api/statistics');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    $room->name,
                    0,
                ],
            ],
        ]);
    }

    // Ikke penge indsat
    public function testDespositDoesNotCount()
    {
        // Sample data
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        // Buy two test product
        $beer = Beer::create([
            'room'      => $room->id,
            'quantity'  => 1,
            'product'   => 'deposit',
            'ipAddress' => request()->ip(),
            'amount'    => 100,
        ]);

        $response = $this->get('/api/statistics');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    $room->name,
                    0,
                ],
            ],
        ]);
    }

    // Sikre at værelse som har vælgt at "opt-out" ikke er med i statistik
    // Tilføj test hvor feltet opt-out tilføjes til "Room" modellen.

    // Tjek det sorteret efter antal (tilføje order by count() stuff)
}
