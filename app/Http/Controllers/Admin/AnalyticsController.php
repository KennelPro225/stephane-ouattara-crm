<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Programme;
use App\Models\Session;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    public function index(Request $request): Response
    {
        $monthsBack = (int) $request->input('range', 12);

        $monthlySessions = Session::selectRaw('SUBSTR(created_at, 1, 7) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths($monthsBack)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $programmePopularity = Programme::withCount('sessions')
            ->orderByDesc('sessions_count')
            ->take(8)
            ->get(['id', 'title', 'sessions_count']);

        $byType = Session::selectRaw('type, COUNT(*) as count')->groupBy('type')->get();
        $bySource = Customer::selectRaw('source, COUNT(*) as count')->groupBy('source')->get();

        $totalSessions = Session::count();
        $converted = Session::whereIn('status', ['confirmed', 'completed'])->count();

        return Inertia::render('Admin/Analytics', [
            'monthlySessions' => $monthlySessions,
            'programmePopularity' => $programmePopularity,
            'sessionsByType' => $byType,
            'customersBySource' => $bySource,
            'conversion' => [
                'pending' => Session::where('status', 'pending')->count(),
                'confirmed' => Session::where('status', 'confirmed')->count(),
                'completed' => Session::where('status', 'completed')->count(),
                'cancelled' => Session::where('status', 'cancelled')->count(),
                'rate' => $totalSessions > 0 ? round($converted / $totalSessions * 100, 1) : 0,
            ],
        ]);
    }
}
