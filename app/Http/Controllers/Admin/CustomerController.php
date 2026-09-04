<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Clients/Index', [
            'customers' => Customer::query()
                ->withCount('bookings')
                ->when($request->input('search'), function ($q, $s) {
                    $q->where(fn ($qq) => $qq->where('first_name', 'like', "%{$s}%")
                        ->orWhere('last_name', 'like', "%{$s}%")
                        ->orWhere('email', 'like', "%{$s}%"));
                })
                ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(Customer $customer): Response
    {
        return Inertia::render('Admin/Clients/Show', [
            'customer' => $customer,
            'bookings' => $customer->bookings()->with('programme')->latest()->get(),
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Customer::STATUSES)],
        ]);

        $customer->update($validated);

        return back()->with('success', 'Statut du client mis à jour.');
    }

    public function addNote(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        $customer->appendNote($validated['note']);
        $customer->save();

        return back()->with('success', 'Note ajoutée.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'clients-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Prénom', 'Nom', 'Email', 'Téléphone', 'Société', 'Statut', 'Source', 'Réservations', 'Inscrit le']);
            Customer::withCount('bookings')->chunk(200, function ($customers) use ($out) {
                foreach ($customers as $c) {
                    fputcsv($out, [$c->id, $c->first_name, $c->last_name, $c->email, $c->phone, $c->company, $c->status, $c->source, $c->bookings_count, $c->created_at]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
