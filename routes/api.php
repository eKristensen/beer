<?php

use App\Beer;
use App\Product;
use App\Room;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'sum'  => $room->sum,
        ],
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
    $beer = $room
        ->beers()
        ->create([
            'quantity'  => $quantity,
            'product'   => $product->id,
            'ipAddress' => request()->ip(),
            'amount'    => -($product->price * $quantity),
        ]);

    return [
            'name'    => $room->name,
            'product' => $product->name,
            'sum'     => $room->sum,
        ];

    // Check integer type
})->where('quantity', '[0-9]+');

Route::get('/refund/{beer}', function (Beer $beer) {
    if (!Room::find($beer->room)->active) {
        return;
    }

    // If refund is not possible then return 403
    if (!$beer->refund) {
        return new JsonResponse(['error' => 'Refund is not possible'], 403);
    }

    return [
        'data' => [
            'refunded' => $beer->refunded,
            'amount'   => $beer->amount,
        ],
    ];
});

Route::get('/products', function () {
    return [
        'data' => DB::table('products')
            ->select('id', 'name')
            ->where('active', '=', true)
            ->get(),
    ];
});

Route::get('/statistics/{product}', function (Product $product) {
    $rooms = DB::table('rooms')
        ->select(DB::raw('sum(beers.quantity) as count, MAX(rooms.name) as name'))
        ->leftJoin('beers', function ($join) use ($product) {
            $join->on('rooms.id', '=', 'beers.room')
                ->join('products', 'beers.product', '=', 'products.id')
                ->where('products.active', '=', true)
                ->where('beers.refunded', '=', false)
                ->whereBetween('beers.created_at', [
                    Carbon::now()
                        ->subDays(30)
                        ->toDateTimeString(),
                    Carbon::now()
                        ->subMinutes(30)
                        ->toDateTimeString(),
                ])
                ->where('products.id', '=', $product->id);
        })
        ->where('rooms.active', '=', true)
        ->where('rooms.statistics', '=', true)
        ->groupBy('rooms.id')
        ->orderBy('count', 'desc')
        ->get();

    $output = [];

    foreach ($rooms as $room) {
        array_push($output, [
            $room->name,
            (int) $room->count,
        ]);
    }

    return [
        'data' => $output,
    ];
});

Route::get('/statistics', function () {
    $rooms = DB::table('rooms')
        ->select(DB::raw('sum(beers.quantity) as count, MAX(rooms.name) as name'))
        ->leftJoin('beers', function ($join) {
            $join->on('rooms.id', '=', 'beers.room')
                ->join('products', 'beers.product', '=', 'products.id')
                ->where('products.active', '=', true)
                ->where('beers.refunded', '=', false)
                ->whereBetween('beers.created_at', [
                    Carbon::now()
                        ->subDays(30)
                        ->toDateTimeString(),
                    Carbon::now()
                        ->subMinutes(30)
                        ->toDateTimeString(),
                ]);
        })
        ->where('rooms.active', '=', true)
        ->where('rooms.statistics', '=', true)
        ->groupBy('rooms.id')
        ->orderBy('count', 'desc')
        ->get();

    $output = [];

    foreach ($rooms as $room) {
        array_push($output, [
            $room->name,
            (int) $room->count,
        ]);
    }

    return [
        'data' => $output,
    ];
});
