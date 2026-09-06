<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgrammeRequest;
use App\Models\AvailabilityRule;
use App\Models\Programme;
use App\Services\AvailabilityService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProgrammeController extends Controller
{
    public function __construct(private ImageService $images, private AvailabilityService $availability) {}

    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Programmes/Index', [
            'programmes' => Programme::query()
                ->withCount(['bookings as confirmed_count' => fn ($q) => $q->whereIn('status', ['confirmed', 'completed'])])
                ->when($request->input('search'), fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
                ->orderByDesc('created_at')
                ->paginate(10)
                ->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Programmes/Create', $this->schedulingContext());
    }

    public function store(StoreProgrammeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->images->store($request->file('image'), 'programmes');
        }

        $programme = Programme::create($data + ['created_by' => $request->user()->id]);

        return $this->redirectToIndex($programme, 'Programme créé.');
    }

    public function edit(Programme $programme): Response
    {
        return Inertia::render('Admin/Programmes/Edit', [
            'programme' => $programme,
            ...$this->schedulingContext(),
        ]);
    }

    public function update(StoreProgrammeRequest $request, Programme $programme): RedirectResponse
    {
        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->images->replace($programme->image_path, $request->file('image'), 'programmes');
        }

        $programme->update($data);

        return $this->redirectToIndex($programme, 'Programme mis à jour.');
    }

    /** The coach's service hours, so the form can show the valid window inline. */
    private function schedulingContext(): array
    {
        AvailabilityRule::ensureDefaults();

        return [
            'availabilityRules' => AvailabilityRule::orderBy('weekday')->get(['weekday', 'is_open', 'start_time', 'end_time']),
        ];
    }

    /**
     * Scheduling over existing client bookings is allowed, but the coach is told
     * which ones now clash so they can reschedule them.
     */
    private function redirectToIndex(Programme $programme, string $success): RedirectResponse
    {
        $redirect = redirect()->route('admin.programmes.index')->with('success', $success);

        $conflicts = $this->availability->conflictingBookings($programme);

        if ($conflicts->isNotEmpty()) {
            $details = $conflicts->take(3)
                ->map(fn ($booking) => $booking->customer->full_name.' ('.$booking->preferred_date->format('d/m/Y').' à '.substr($booking->preferred_time, 0, 5).')')
                ->implode(', ');

            $extra = $conflicts->count() > 3 ? ' et '.($conflicts->count() - 3).' autre(s)' : '';

            $redirect->with('warning', $conflicts->count().' réservation(s) client tombent désormais pendant ce programme : '.$details.$extra.'. Reprogrammez-les depuis l’écran Réservations.');
        }

        return $redirect;
    }

    public function destroy(Programme $programme): RedirectResponse
    {
        $programme->delete();

        return back()->with('success', 'Programme archivé.');
    }
}
