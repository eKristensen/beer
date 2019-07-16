<?php

namespace App\Http\Controllers;

use App\Product;
use App\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::where('active', '=', '1')->get();
        $products = Product::where('active', '=', '1')->get();

        return view('rooms.index', compact('rooms', 'products'));
    }

    public function store()
    {
        $validated = request()->validate([
            'id'   => 'required|integer|gt:0',
            'name' => 'required',
        ]);

        Room::create($validated);

        return back();
    }

    public function patch()
    {
        $validated = request()->validate([
            'id'     => 'required',
            'name'   => 'nullable',
            'active' => 'nullable|in:1',
        ]);

        // Get the room
        $room = Room::find($validated['id']);

        // Set room name if name is set, and not empty, avoid empty names
        if (isset($validated['name']) && $validated['name'] != '') {
            $room->name = $validated['name'];
        }

        // Set active to true if it is set otherwise to false
        if (isset($validated['active'])) {
            $room->active = true;
        } else {
            $room->active = false;
        }

        // Save changes
        $room->save();

        // Return to previous page
        return back();
    }

    public function edit()
    {
        $rooms = Room::all();

        return view('rooms.edit', compact('rooms'));
    }

    public function show(Room $room)
    {
        $beers = $room->beers;

        return view('rooms.show', compact('room', 'beers'));
    }
}
