<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    /** Allowed status transitions, keyed by current status. */
    private const TRANSITIONS = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['completed', 'cancelled'],
        'completed' => [],
        'cancelled' => [],
    ];

    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Reservations/Index', [
            'bookings' => Booking::with(['customer', 'programme'])
                ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'filters' => $request->only(['status']),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Booking::STATUSES)],
        ]);

        $allowed = self::TRANSITIONS[$booking->status] ?? [];
        if ($validated['status'] !== $booking->status && ! in_array($validated['status'], $allowed, true)) {
            throw ValidationException::withMessages([
                'status' => "Impossible de passer de « {$booking->status} » à « {$validated['status']} ».",
            ]);
        }

        if ($validated['status'] === 'confirmed' && $booking->programme && $booking->programme->available_seats <= 0) {
            throw ValidationException::withMessages([
                'status' => 'Ce programme est complet : aucune place disponible pour confirmer cette réservation.',
            ]);
        }

        $booking->status = $validated['status'];
        if ($validated['status'] === 'confirmed') {
            $booking->confirmed_at = now();
        }
        $booking->save();

        return back()->with('success', 'Statut de la réservation mis à jour.');
    }
}
