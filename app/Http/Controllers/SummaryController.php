<?php

namespace App\Http\Controllers;

use App\PersonToRoomSummary;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function store()
    {
        $validated = request()->validate([
            'id'     => 'required',
            'room_id'   => 'required',
        ]);

        PersonToRoomSummary::create($validated);

        return back();
    }

    public function patch()
    {
        $validated = request()->validate([
            'id'     => 'required',
            'room_id'   => 'required',
        ]);

        $summary_item = PersonToRoomSummary::find($validated['id']);
        $summary_item->room_id = $validated['room_id'];
        $summary_item->save();

        return back();
    }
}
