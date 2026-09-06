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
        $month = $this->resolveMonth($request->query('month'));
        $date = $this->resolveDate($request->query('date'));

        return Inertia::render('Reserver', [
            'programmes' => Programme::published()->orderBy('title')->get(['id', 'title', 'max_participants']),
            'selectedProgrammeId' => Programme::published()->find($request->query('programme'))?->id,
            'month' => $month,
            'availableDates' => $availability->availableDatesInMonth($month),
            'availableSlots' => $date ? $availability->slotsForDate($date) : [],
        ]);
    }

    private function resolveMonth(?string $month): string
    {
        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
            try {
                return Carbon::createFromFormat('Y-m-d', $month.'-01')->format('Y-m');
            } catch (\Exception) {
                // fall through to the current month
            }
        }

        return today()->format('Y-m');
    }

    private function resolveDate(?string $date): ?Carbon
    {
        if (! $date) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        } catch (\Exception) {
            return null;
        }
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
