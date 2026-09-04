<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Notifications\AdminNewContact;
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

        $customer = Customer::firstOrNew(['email' => strtolower($validated['email'])]);
        $customer->fill([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'source' => $customer->exists ? $customer->source : 'website',
            'status' => $customer->exists ? $customer->status : Customer::STATUSES[0],
        ]);
        $customer->appendNote('Message de contact : '.$validated['message']);
        $customer->save();

        User::query()->where('role', 'admin')->chunkById(50, function ($admins) use ($customer) {
            $admins->each(fn ($admin) => $admin->notify(new AdminNewContact($customer)));
        });

        return redirect()->route('contact')->with('success', 'Merci ! Votre message a bien été envoyé. Nous vous répondrons sous 48h.');
    }
}
