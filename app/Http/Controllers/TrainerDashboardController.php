<?php

namespace App\Http\Controllers;

class TrainerDashboardController extends Controller
{
    public function index()
    {
        return view('trainer.dashboard');
    }
}