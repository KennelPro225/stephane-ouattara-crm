<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailabilityRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AvailabilityController extends Controller
{
    public function index(): Response
    {
        AvailabilityRule::ensureDefaults();

        return Inertia::render('Admin/Availability/Index', [
            'rules' => AvailabilityRule::orderByRaw("weekday = 0, weekday")->get(),
            'slotMinutes' => (int) setting('booking_slot_minutes', 60),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slot_minutes' => ['required', 'integer', 'min:15', 'max:240'],
            'rules' => ['required', 'array', 'size:7'],
            'rules.*.id' => ['required', 'integer', 'exists:availability_rules,id'],
            'rules.*.is_open' => ['boolean'],
            'rules.*.start_time' => ['nullable', 'date_format:H:i'],
            'rules.*.end_time' => ['nullable', 'date_format:H:i', 'after:rules.*.start_time'],
        ]);

        foreach ($validated['rules'] as $rule) {
            AvailabilityRule::whereKey($rule['id'])->update([
                'is_open' => $rule['is_open'] ?? false,
                'start_time' => $rule['start_time'] ?? null,
                'end_time' => $rule['end_time'] ?? null,
            ]);
        }

        set_setting('booking_slot_minutes', $validated['slot_minutes']);

        return back()->with('success', 'Disponibilités mises à jour.');
    }
}
