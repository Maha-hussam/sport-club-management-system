<?php

namespace App\Http\Controllers;

class PlayerDashboardController extends Controller
{
    public function index()
    {
        return view('player.dashboard');
    }
}