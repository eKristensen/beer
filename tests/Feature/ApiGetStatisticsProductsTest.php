<?php

namespace Tests\Feature;

use App\Beer;
use App\Product;
use App\Room;
use Tests\TestCase;

class ApiGetStatisticsProductsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testThatItLoads()
    {
        $response = $this->get('/api/statistics/products');

        $response->assertStatus(200);
    }

    public function testSingleProductLoads()
    {
        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $response = $this->get('/api/statistics/products');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    'id'   => $product->id,
                    'name' => $product->name,
                ],
            ],
        ]);
    }

    // Test inactive does not show
    public function testInactiveIsNotShownWithNoPurchases()
    {
        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
            'active'   => false,
        ]);

        $response = $this->get('/api/statistics/products');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertDontSee($product->name);
    }

    // Test inactive with purchase does show
    public function testInactiveWithPurchasesIsShown()
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

        // Buy one test product on the inactive product 3
        Beer::create([
            'room'      => $room->id,
            'quantity'  => $quantity,
            'product'   => $product->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product->price * $quantity),
        ]);

        // Set time to be 31 minutes old so the purchase is included in the statistics
        \DB::update(
            'UPDATE beers SET created_at = ?',
            [\Carbon\Carbon::now()->subMinutes(31)->toDateTimeString()]
        );

        $response = $this->get('/api/statistics/products');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertSee($product->name);
    }

    // Test more than one product can show
    public function testInctiveProductsWithPurchaseDoesShows()
    {
        // Sample data
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $product_1 = Product::create([
            'name'     => 'Test product 1',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $product_2 = Product::create([
            'name'     => 'Test product 2',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $product_3 = Product::create([
            'name'     => 'Test product 3',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
            'active'   => false,
        ]);

        $quantity = 2;

        // Buy one test product on the inactive product 3
        Beer::create([
            'room'      => $room->id,
            'quantity'  => $quantity,
            'product'   => $product_3->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product_3->price * $quantity),
        ]);

        // Set time to be 31 minutes old so the purchase is included in the statistics
        \DB::update(
            'UPDATE beers SET created_at = ?',
            [\Carbon\Carbon::now()->subMinutes(31)->toDateTimeString()]
        );

        $response = $this->get('/api/statistics/products');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    'id'   => $product_1->id,
                    'name' => $product_1->name,
                ],
                [
                    'id'   => $product_2->id,
                    'name' => $product_2->name,
                ],
                [
                    'id'   => $product_3->id,
                    'name' => $product_3->name,
                ],
            ],
        ]);

        // Check inactive does not show again
        $response->assertSee($product_3->name);
    }

    // Test inactive products without any purchase does not show
    public function testInactiveWithTooNewPurchaseDoesNotShow()
    {
        // Sample data
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $product_1 = Product::create([
            'name'     => 'Test product 1',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $product_2 = Product::create([
            'name'     => 'Test product 2',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $product_3 = Product::create([
            'name'     => 'Test product 3',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
            'active'   => false,
        ]);

        $quantity = 2;

        // Buy one test product on the inactive product 3
        Beer::create([
            'room'      => $room->id,
            'quantity'  => $quantity,
            'product'   => $product_3->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product_3->price * $quantity),
        ]);

        $response = $this->get('/api/statistics/products');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    'id'   => $product_1->id,
                    'name' => $product_1->name,
                ],
                [
                    'id'   => $product_2->id,
                    'name' => $product_2->name,
                ],
            ],
        ]);

        // Check inactive does not show again
        $response->assertDontSee($product_3->name);
    }

    // Test inactive products without any purchase does not show
    public function testInactiveWithTooOldPurchaseDoesNotShow()
    {
        // Sample data
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $product_1 = Product::create([
            'name'     => 'Test product 1',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $product_2 = Product::create([
            'name'     => 'Test product 2',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $product_3 = Product::create([
            'name'     => 'Test product 3',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
            'active'   => false,
        ]);

        $quantity = 2;

        // Buy one test product on the inactive product 3
        Beer::create([
            'room'      => $room->id,
            'quantity'  => $quantity,
            'product'   => $product_3->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product_3->price * $quantity),
        ]);

        // Set time to be 31 days behind in time for all purchases
        \DB::update(
            'UPDATE beers SET created_at = ?',
            [\Carbon\Carbon::now()->subDays(31)->toDateTimeString()]
        );

        $response = $this->get('/api/statistics/products');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    'id'   => $product_1->id,
                    'name' => $product_1->name,
                ],
                [
                    'id'   => $product_2->id,
                    'name' => $product_2->name,
                ],
            ],
        ]);

        // Check inactive does not show again
        $response->assertDontSee($product_3->name);
    }

    // Test inactive products without any purchase does not show
    public function testInactiveWithNoPurchaseDoesNotShow()
    {
        $product_1 = Product::create([
            'name'     => 'Test product 1',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $product_2 = Product::create([
            'name'     => 'Test product 2',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $product_3 = Product::create([
            'name'     => 'Test product 3',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
            'active'   => false,
        ]);

        $response = $this->get('/api/statistics/products');

        // Expect a code 200 OK
        $response->assertStatus(200);

        // Assert proper JSON response
        $response->assertJson([
            'data' => [
                [
                    'id'   => $product_1->id,
                    'name' => $product_1->name,
                ],
                [
                    'id'   => $product_2->id,
                    'name' => $product_2->name,
                ],
            ],
        ]);

        // Check inactive does not show again
        $response->assertDontSee($product_3->name);
    }
}
