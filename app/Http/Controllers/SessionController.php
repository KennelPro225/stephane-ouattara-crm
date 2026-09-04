<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSessionRequest;
use App\Models\Programme;
use App\Models\Session;
use App\Services\SessionService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SessionController extends Controller
{
    public function __construct(private SessionService $sessions) {}

    public function create(Request $request): Response
    {
        return Inertia::render('Sessions/Create', [
            'programmes' => Programme::published()->orderBy('start_date')->get(['id', 'title', 'start_date', 'type']),
            'types' => Session::TYPES,
            'selectedType' => in_array($request->query('type'), Session::TYPES) ? $request->query('type') : null,
            'selectedProgramme' => Programme::published()->find($request->query('programme'))?->id,
        ]);
    }

    public function store(StoreSessionRequest $request)
    {
        $this->sessions->createBooking($request->validated());

        return redirect()
            ->route('reserver-une-session')
            ->with('success', 'Votre demande de session a bien été enregistrée. Un email de confirmation vous a été envoyé.');
    }
}
