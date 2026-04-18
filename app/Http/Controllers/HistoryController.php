<?php

namespace App\Http\Controllers;

class HistoryController extends Controller
{
    public function index()
    {
        $history = auth()->user()
            ->searchHistory()
            ->latest('created_at')
            ->take(50)
            ->get();

        return view('user.history', compact('history'));
    }
}


