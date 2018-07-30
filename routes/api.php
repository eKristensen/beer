<?php

use Illuminate\Http\Request;
use App\Http\Resources\RoomResource;
use App\Http\Resources\BeerResource;
use \App\Room;
use \App\Beer;

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

Route::get('/buy/{room}/{type}/{quantity}', function (Room $room, $type, $quantity) {
	if (!$room->active) return null;
	$beer = new Beer();
	$beer->room = $room->id;
	$beer->quantity = $quantity;
	$beer->type = $type;
	$beer->ipAddress = request()->ip();
	if ($type == "beer") $beer->amount = -4 * $quantity;
	if ($type == "cider") $beer->amount = -5 * $quantity;
	/*
	if ($type == "somersby") {
		$beer->type = "cider";
		$beer->amount = -2 * $quantity;
	}*/
	$beer->save();

    return new RoomResource($room);
});

Route::get('/refund/{beer}', function (Beer $beer) {
	if (!Room::find($beer->room)->active) return null;
	$beer->refund;

    return new BeerResource($beer);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
