<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Programme;
use App\Models\Session;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $totalSessions = Session::count();
        $confirmedSessions = Session::whereIn('status', ['confirmed', 'completed'])->count();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_sessions' => $totalSessions,
                'pending_sessions' => Session::where('status', 'pending')->count(),
                'customers' => Customer::count(),
                'published_programmes' => Programme::published()->count(),
                'conversion_rate' => $totalSessions > 0 ? round($confirmedSessions / $totalSessions * 100, 1) : 0,
            ],
            'recentBookings' => Session::with(['customer', 'programme'])
                ->latest()
                ->take(8)
                ->get(),
            'upcomingProgrammes' => Programme::published()
                ->where('start_date', '>=', now()->toDateString())
                ->orderBy('start_date')
                ->take(5)
                ->get(),
        ]);
    }
}
