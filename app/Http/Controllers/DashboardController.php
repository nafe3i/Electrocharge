<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Connector;
use App\Models\ConnectorStatus;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\Station;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $total = ConnectorStatus::count() ?: 1;

        return view('admin.dashboard', [
            'totalStations' => Station::count(),
            'activeStations' => Station::where('is_active', true)->count(),
            'totalUsers' => User::count(),
            'totalReviews' => Review::count(),
            'totalFavorites' => Favorite::count(),
            'totalConnectors' => Connector::count(),
            'libreCount' => ConnectorStatus::where('status', 'libre')->count(),
            'occupeeCount' => ConnectorStatus::where('status', 'occupee')->count(),
            'horsServiceCount' => ConnectorStatus::where('status', 'hors_service')->count(),
            'recentStations' => Station::latest()->take(5)->get(),
            'recentLogs' => AdminLog::with('admin')->latest()->take(8)->get(),
        ]);
    }
}
