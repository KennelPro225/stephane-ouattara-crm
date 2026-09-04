<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Contact', [
            'testimonials' => \App\Models\Testimonial::where('approved', true)
                ->orderByDesc('featured')
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        Customer::updateOrCreate(
            ['email' => strtolower($validated['email'])],
            [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'source' => 'website',
                'status' => Customer::STATUSES[0],
                'notes' => 'Message de contact : '.$validated['message'],
            ]
        );

        return redirect()->route('contact')->with('success', 'Merci ! Votre message a bien été envoyé. Nous vous répondrons sous 48h.');
    }
}
