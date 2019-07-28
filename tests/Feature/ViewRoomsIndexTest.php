<?php

namespace Tests\Feature;

use App\Product;
use App\Room;
use Tests\TestCase;

class ViewRoomsIndexTest extends TestCase
{
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
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232.00',
        ]);

        $response = $this->get('/rooms');

        $response->assertStatus(200);

        $response->assertSee($product->name);
        $response->assertSee($product->price.' kr');
    }

    public function testInactiveProductDoesNotShow()
    {
        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232',
            'active'   => false,
        ]);

        $response = $this->get('/rooms');

        $response->assertStatus(200);

        $response->assertDontSee($product->name);
    }

    public function testProductQuantityWorks()
    {
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $product = Product::create([
            'name'     => 'Test product',
            'color'    => 'fff',
            'quantity' => '1,2,5',
            'price'    => '1232',
        ]);

        $response = $this->get('/rooms');

        $response->assertStatus(200);

        // Colspan must be right
        $response->assertSee('colspan="'.count($product->quantities()).'"');

        // Check buy buttons does show for every price
        foreach ($product->quantities() as $quantity) {
            $response->assertSee('<button type="button" class="button is-success"'
                .'  style="background-color: #'.$product->color.';border-color:'
                .' #'.$product->color.';"  onclick="buy('.$room->id.',\''
                .$product->id.'\','.$quantity.')">+'.$quantity.'</button>');
        }
    }

    public function testRoomShows()
    {
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $response = $this->get('/rooms');

        $response->assertStatus(200);

        $response->assertSee($room->name);
    }

    public function testInactiveRoomDoesNotShow()
    {
        $room = Room::create([
            'id'     => 1,
            'name'   => 'Test room',
            'active' => false,
        ]);

        $response = $this->get('/rooms');

        $response->assertStatus(200);

        $response->assertDontSee($room->name);
    }
}
