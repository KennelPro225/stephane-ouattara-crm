<?php

namespace App\Services;

use App\Jobs\SendBookingConfirmation;
use App\Models\Customer;
use App\Models\Session;

class SessionService
{
    public function createBooking(array $data): Session
    {
        $customer = Customer::firstOrNew(['email' => strtolower($data['email'])]);
        $customer->fill([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'company' => $data['company'] ?? null,
            'role' => $data['role'] ?? null,
            'city' => $data['city'] ?? null,
            'source' => $customer->exists ? $customer->source : 'website',
            'status' => $customer->exists ? $customer->status : Customer::STATUSES[0],
        ]);
        if (! empty($data['message'])) {
            $customer->appendNote('Message de réservation : '.$data['message']);
        }
        $customer->save();

        $session = Session::create([
            'customer_id' => $customer->id,
            'programme_id' => $data['programme_id'] ?? null,
            'type' => $data['type'],
            'preferred_date' => $data['preferred_date'],
            'preferred_time' => $data['preferred_time'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => Session::STATUSES[0],
        ]);

        SendBookingConfirmation::dispatch($session);

        return $session;
    }
}
