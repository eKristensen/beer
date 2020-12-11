<?php

namespace Tests\Feature;

use App\Product;
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
    public function testInactiveIsShown()
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
        $response->assertSee($product->name);
    }

    // Test more than one product can show
    public function testInctiveProductsShows()
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
                [
                    'id'   => $product_3->id,
                    'name' => $product_3->name,
                ],
            ],
        ]);

        // Check inactive does not show again
        $response->assertSee($product_3->name);
    }
}
