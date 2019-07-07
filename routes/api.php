<?php

use App\Http\Resources\BeerResource;
use App\Http\Resources\RoomResource;
use App\Product;
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
    return new RoomResource($room);
});

Route::get('/buy/{room}/{product}/{quantity}', function (Room $room, Product $product, $quantity) {
    if (!$room->active) {
        return null;
    }
    if (!$product->active) {
        return null;
    }
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
    ;
});

Route::get('/refund/{beer}', function (Beer $beer) {
    if (!Room::find($beer->room)->active) {
        return null;
    }
    $beer->refund;

    return new BeerResource($beer);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
