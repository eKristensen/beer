<?php

namespace Tests\Feature;

use App\Beer;
use App\Product;
use App\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiGetStatisticsForProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNotFound()
    {
        $response = $this->get('/api/statistics/1');

        $response->assertStatus(404);
    }

    public function testPageLoads()
    {
        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $response = $this->get('/api/statistics/'.$product->id);

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

        $response = $this->get('/api/statistics/'.$product->id);

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

        $response = $this->get('/api/statistics/'.$product->id);

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

        $response = $this->get('/api/statistics/'.$product->id);

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

        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $response = $this->get('/api/statistics/'.$product->id);

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

        $response = $this->get('/api/statistics/'.$product->id);

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

        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $response = $this->get('/api/statistics/'.$product->id);

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
    public function testStatisticsSetToFalseIsRespected()
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

        $room->statistics = false;
        $room->save();
        $this->assertEquals(Room::find($room->id)->statistics, false);

        $response = $this->get('/api/statistics/'.$product->id);

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertDontSee($room->name);
    }

    // Viser korrekt værelsesnavn og tælling når flere værelser hentes ned
    // Tjek det sorteret efter antal (tilføje order by count() stuff)
    public function testCheckOrderingOfMultiplateOrderes()
    {
        // Sample data
        $room_1 = Room::create([
            'id'   => 1,
            'name' => 'Test room #1',
        ]);

        $room_2 = Room::create([
            'id'   => 2,
            'name' => 'Test room #2',
        ]);

        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $quantity = 2;

        // Buy two test product
        $beer_1 = Beer::create([
            'room'      => $room_1->id,
            'quantity'  => $quantity,
            'product'   => $product->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product->price * $quantity),
        ]);

        $beer_2 = Beer::create([
            'room'      => $room_2->id,
            'quantity'  => 2 * $quantity,
            'product'   => $product->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product->price * $quantity),
        ]);

        $response = $this->get('/api/statistics/'.$product->id);

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    $room_2->name,
                    $beer_2->quantity,
                ],
                [
                    $room_1->name,
                    $beer_1->quantity,
                ],
            ],
        ]);
    }

    // Test only count this product
    public function testOnlyCountThisProduct()
    {
        // Sample data
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $product_1 = Product::create([
            'name'     => 'Test product #1',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $product_2 = Product::create([
            'name'     => 'Test product #2',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $quantity = 2;

        // Buy two test product
        $beer_1 = Beer::create([
            'room'      => $room->id,
            'quantity'  => $quantity,
            'product'   => $product_1->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product_1->price * $quantity),
        ]);

        $beer_2 = Beer::create([
            'room'      => $room->id,
            'quantity'  => 2 * $quantity,
            'product'   => $product_2->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product_2->price * $quantity),
        ]);

        $response = $this->get('/api/statistics/'.$product_1->id);

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    $room->name,
                    $beer_1->quantity,
                ],
            ],
        ]);
    }
}
