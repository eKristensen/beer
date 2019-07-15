<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use \App\Beer;

class BeerController extends Controller
{
    public function refund()
    {
        $beers = Beer::where('amount', '<', '0')
            ->where('created_at', '>', Carbon::now()
                                        ->subMinutes(30)
                                        ->toDateTimeString())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('beer.refund', compact('beers'));
    }
}
