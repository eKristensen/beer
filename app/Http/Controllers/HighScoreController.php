<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HighScoreController extends Controller
{
    public function index() {
        return view('highscore.index');
    }
}
