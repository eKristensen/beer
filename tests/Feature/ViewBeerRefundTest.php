<?php

namespace Tests\Feature;

use App\Beer;
use App\Product;
use App\Room;
use Tests\TestCase;

class ViewBeerRefundTest extends TestCase
{
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

        $response = $this->get('/refund');

        $response->assertStatus(200);

        $response->assertSee('-'.($product->price * 2).'.00 kr.');
        $response->assertSee($product->name);
    }

    public function testDontShowOldPurchase()
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

        $beer = new Beer();
        $beer->room = $room->id;
        $beer->quantity = $quantity;
        $beer->product = $product->id;
        $beer->ipAddress = request()->ip();
        $beer->amount = -($product->price * $quantity);
        $beer->save();

        // Set time to be 35 minutes behind in time for all purchases
        \DB::update(
            'UPDATE beers SET created_at = ?',
            [\Carbon\Carbon::now()->subMinutes(35)->toDateTimeString()]
        );

        $response = $this->get('/refund');

        $response->assertStatus(200);

        $response->assertDontSee('-'.($product->price * 2).'.00 kr.');
        $response->assertDontSee($product->name);
    }
}
