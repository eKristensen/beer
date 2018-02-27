<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Room;

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
	$rooms = $room->all();

    	return view('rooms.index', compact('rooms'));
    }
}
