<?php

namespace App\Services;

use App\Jobs\SendBookingConfirmation;
use App\Models\Customer;
use App\Models\Session;

class SessionService
{
    public function createBooking(array $data): Session
    {
        $customer = Customer::updateOrCreate(
            ['email' => strtolower($data['email'])],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'company' => $data['company'] ?? null,
                'role' => $data['role'] ?? null,
                'city' => $data['city'] ?? null,
                'source' => 'website',
            ]
        );

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
