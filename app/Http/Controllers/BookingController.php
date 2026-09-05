<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Programme;
use App\Models\User;
use App\Notifications\AdminNewBooking;
use App\Notifications\BookingConfirmation;
use App\Services\AvailabilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function create(Request $request, AvailabilityService $availability): Response
    {
        $date = Carbon::parse($request->query('date') ?: today());

        return Inertia::render('Reserver', [
            'programmes' => Programme::published()->orderBy('title')->get(['id', 'title', 'max_participants']),
            'selectedProgrammeId' => Programme::published()->find($request->query('programme'))?->id,
            'availableSlots' => $availability->slotsForDate($date),
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $customer = Customer::firstOrNew(['email' => strtolower($data['email'])]);
        $customer->fill([
            'first_name' => $data['prenom'],
            'last_name' => $data['nom'],
            'phone' => $data['tel'] ?? $customer->phone,
            'company' => $data['societe'] ?? $customer->company,
            'job_title' => $data['poste'] ?? $customer->job_title,
            'source' => $customer->exists ? $customer->source : 'Site web',
            'status' => $customer->exists ? $customer->status : 'nouveau',
        ]);
        if (! empty($data['message'])) {
            $customer->appendNote('Message de réservation : '.$data['message']);
        }
        $customer->save();

        $booking = Booking::create([
            'customer_id' => $customer->id,
            'programme_id' => $data['programme_id'] ?? null,
            'service_type' => StoreBookingRequest::SERVICE_MAP[$data['service']],
            'preferred_date' => $data['date'],
            'preferred_time' => $data['heure'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);
        $booking->load(['customer', 'programme']);

        $customer->notify(new BookingConfirmation($booking));
        User::where('role', 'admin')->get()->each(fn ($admin) => $admin->notify(new AdminNewBooking($booking)));

        return back();
    }
}
