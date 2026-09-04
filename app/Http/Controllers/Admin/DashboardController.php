<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Programme;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $total = Booking::count();
        $confirmed = Booking::whereIn('status', ['confirmed', 'completed'])->count();

        $sources = Customer::selectRaw('COALESCE(source, ?) as source, COUNT(*) as count', ['Autre'])
            ->groupBy('source')
            ->orderByDesc('count')
            ->get();
        $sourcesTotal = max(1, $sources->sum('count'));

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'bookings' => $total,
                'customers' => Customer::count(),
                'activeProgrammes' => Programme::published()->count(),
                'conversionRate' => $total > 0 ? round($confirmed / $total * 100, 1) : 0,
            ],
            'recentBookings' => Booking::with(['customer', 'programme'])->latest()->take(6)->get(),
            'upcomingProgrammes' => Programme::published()->where('start_date', '>=', now()->toDateString())->orderBy('start_date')->take(4)->get(),
            'sources' => $sources->map(fn ($s) => [
                'label' => $s->source,
                'pct' => round($s->count / $sourcesTotal * 100).'%',
            ]),
        ]);
    }
}
