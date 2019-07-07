<?php

namespace Tests\Feature;

use App\Product;
use App\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowRoomsTest extends TestCase
{
    // https://laravel.com/docs/5.8/database-testing#using-migrations
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPageLoads()
    {
        $response = $this->get('/rooms');

        $response->assertStatus(200);
    }

    public function testProductShows()
    {
        $product = Product::create([
            'name' => 'Test product',
            'color' => 'fff',
            'quantity' => '1,2,5',
            'price' => '1232.00',
        ]);

        $response = $this->get('/rooms');

        $response->assertStatus(200);

        $response->assertSee($product->name);
        $response->assertSee($product->price . " kr");
    }

    public function testProductDisabled()
    {
        $product = Product::create([
            'name' => 'Test product',
            'color' => 'fff',
            'quantity' => '1,2,5',
            'price' => '1232',
            'active' => false,
        ]);

        $response = $this->get('/rooms');

        $response->assertStatus(200);

        $response->assertDontSee($product->name);
    }

    public function testRoomShows()
    {
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room',
        ]);

        $response = $this->get('/rooms');

        $response->assertStatus(200);

        $response->assertSee($room->name);
    }

    public function testRoomDisabled()
    {
        $room = Room::create([
            'id' => 1,
            'name' => 'Test room',
            'active' => false,
        ]);

        $response = $this->get('/rooms');

        $response->assertStatus(200);

        $response->assertDontSee($room->name);
    }
}
