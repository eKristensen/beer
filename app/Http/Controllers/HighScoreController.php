<?php

namespace App\Http\Controllers;

class HighScoreController extends Controller
{
    public function index()
    {
        return view('highscore.index');
    }
}
