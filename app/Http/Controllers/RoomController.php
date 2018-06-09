<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Room;
use \App\Beer;

class RoomController extends Controller
{
    public function create() {
    	return view('rooms.create');
    }

    public function store() {
    	$validated = request()->validate([
    		'id' => 'required',
    		'name' => 'required'
    	]);

    	// Create and save user
    	Room::create($validated);

    	return back();
    }

    public function index(Room $room) {
	$rooms = $room->where('active','=','1')->get();

    	return view('rooms.index', compact('rooms'));
    }

    public function edit(Room $room) {
    $rooms = $room->all();

        return view('rooms.edit', compact('rooms'));
    }

    public function depositShow() {
        $rooms = Room::all();

        return view('rooms.deposit', compact('rooms'));
    }

    public function depositStore() {
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

    public function patch() {
        $validated = request()->validate([
            'id' => 'required',
            'name' => 'nullable',
            'active' => 'nullable'
        ]);

        $room = Room::find($validated['id']);
        if ($validated['name'] != "") $room->name = $validated['name'];
        if (isset($validated['active'])) $room->active = true;
        else $room->active = false;
        $room->save();

        return back();
    }

    public function show(Room $room) {
       $beers = $room->beers;
        return view('rooms.show',compact('room','beers'));
    }
}
