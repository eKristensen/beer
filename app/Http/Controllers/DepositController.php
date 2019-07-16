<?php

namespace App\Http\Controllers;

use App\Beer;
use App\Room;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $total = new Beer();
        $diff = $total->where('refunded', '=', 0)->sum('amount');

        return view('rooms.deposit', compact('rooms', 'diff'));
    }

    public function store()
    {
        $validated = request()->validate([
            'room'   => 'required',
            'amount' => 'required|numeric',
        ]);

        $validated['type'] = 'deposit';
        $validated['quantity'] = 1;
        $validated['ipAddress'] = request()->ip();

        Beer::create($validated);

        return back();
    }
}
