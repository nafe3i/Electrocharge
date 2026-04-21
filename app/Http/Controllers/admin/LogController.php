<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminLog;

class LogController extends Controller
{
    public function index()
    {
        // $this->authorize('view_logs');
        $logs = AdminLog::with('admin')
            ->latest('created_at')
            ->paginate(20);
        return view('admin.log.index', compact('logs'));
    }
}
