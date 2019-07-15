<?php

use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \App\Beer;
use \App\Room;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/sum/{room}', function (Room $room) {
    return [
        'data' => [
            'name' => $room->name,
            'sum' => $room->sum,
        ]
    ];
});

Route::get('/buy/{room}/{product}/{quantity}', function (Room $room, Product $product, $quantity) {

    // Check if room is cative or not
    if (!$room->active) {
        return new JsonResponse(['error' => 'Room inactive'], 403);
    }

    // Check if product is active or not
    if (!$product->active) {
        return new JsonResponse(['error' => 'Product inactive'], 403);
    }

    // Create purchase
    $beer = new Beer();
    $beer->room = $room->id;
    $beer->quantity = $quantity;
    $beer->product = $product->id;
    $beer->ipAddress = request()->ip();
    $beer->amount = -($product->price * $quantity);
    $beer->save();

    return [
            'name' => $room->name,
            'product' => $product->name,
            'sum' => $room->sum,
        ];

    // Check integer type
})->where('quantity', '[0-9]+');

Route::get('/refund/{beer}', function (Beer $beer) {
    if (!Room::find($beer->room)->active) {
        return null;
    }

    // If refund is not possible then return 403
    if (!$beer->refund) {
        return new JsonResponse(['error' => 'Refund is not possible'], 403);
    }

    return [
        'data' => [
            'refunded' => $beer->refunded,
            'amount' => $beer->amount,
        ]
    ];
});
