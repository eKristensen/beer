<?php

namespace Tests\Feature;

use App\Beer;
use App\Product;
use App\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewBeerRefundTest extends TestCase
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
        $response = $this->get('/refund');

        $response->assertStatus(200);
    }

    public function testShowPurchase()
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

        $response = $this->get('/refund');

        $response->assertStatus(200);

        $response->assertSee("-".($product->price*2).".00 kr.");
        $response->assertSee($product->name);
    }
}
