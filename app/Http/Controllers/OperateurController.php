<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OperateurController extends Controller
{
    public function dashboard()
    {
        $stations = auth()->user()
            ->stations()
            ->with(['connectors.status'])
            ->get();

        return view('operator.dashboard', compact('stations'));
    }
}
