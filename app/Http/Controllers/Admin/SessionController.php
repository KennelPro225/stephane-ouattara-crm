<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class SessionController extends Controller
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
        return Inertia::render('Admin/Sessions/Index', [
            'sessions' => Session::query()
                ->with(['customer:id,first_name,last_name,email', 'programme:id,title'])
                ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
                ->when($request->input('programme_id'), fn ($q, $id) => $q->where('programme_id', $id))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'filters' => $request->only(['status', 'programme_id']),
        ]);
    }

    public function update(Request $request, Session $session): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Session::STATUSES)],
        ]);

        $allowed = self::TRANSITIONS[$session->status] ?? [];

        if ($validated['status'] !== $session->status && ! in_array($validated['status'], $allowed, true)) {
            throw ValidationException::withMessages([
                'status' => "Impossible de passer une réservation de « {$session->status} » à « {$validated['status']} ».",
            ]);
        }

        if ($validated['status'] === 'confirmed' && $session->programme && $session->programme->available_seats <= 0) {
            throw ValidationException::withMessages([
                'status' => 'Ce programme est complet : aucune place disponible pour confirmer cette réservation.',
            ]);
        }

        $session->status = $validated['status'];
        if ($validated['status'] === 'confirmed') {
            $session->confirmed_at = now();
        }
        $session->save();

        return back()->with('success', 'Statut de la réservation mis à jour.');
    }
}
