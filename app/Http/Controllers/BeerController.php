<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Beer;

class BeerController extends Controller
{
    public function refund() {
		$beers = Beer::where('type','NOT LIKE','deposit')->where('created_at','>',\Carbon\Carbon::now()->subMinutes(30)->toDateTimeString())->orderBy('created_at', 'desc')->get();

	    return view('beer.refund', compact('beers'));
    }
}
