<?php

namespace App\Http\Controllers;

use App\Beer;
use App\Product;
use App\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function store()
    {
        $validated = request()->validate([
            'id' => 'required|integer|gt:0',
            'name' => 'required'
        ]);

        // Create and save user
        Room::create($validated);

        return back();
    }

    public function index()
    {
        $rooms = Room::where('active', '=', '1')->get();
        $products = Product::where('active', '=', '1')->get();

        return view('rooms.index', compact('rooms', 'products'));
    }

    public function edit()
    {
        $rooms = Room::all();

        return view('rooms.edit', compact('rooms'));
    }

    public function depositShow()
    {
        $rooms = Room::all();
        $total = new Beer();
        $diff = $total->where('refunded', '=', 0)->sum('amount');

        return view('rooms.deposit', compact('rooms', 'diff'));
    }

    public function depositStore()
    {
        $validated = request()->validate([
            'room' => 'required',
            'amount' => 'required|numeric'
        ]);

        $validated['type'] = 'deposit';
        $validated['quantity'] = 1;
        $validated['ipAddress'] = request()->ip();


        Beer::create($validated);

        return back();
    }

    public function patch()
    {
        $validated = request()->validate([
            'id' => 'required',
            'name' => 'nullable',
            'active' => 'nullable'
        ]);

        $room = Room::find($validated['id']);
        if ($validated['name'] != "") {
            $room->name = $validated['name'];
        }
        if (isset($validated['active'])) {
            $room->active = true;
        } else {
            $room->active = false;
        }
        $room->save();

        return back();
    }

    public function show(Room $room)
    {
        $beers = $room->beers;
        return view('rooms.show', compact('room', 'beers'));
    }
}
