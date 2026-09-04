<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProgrammeController extends Controller
{
    public function index(Request $request): Response
    {
        $sort = $request->input('sort', 'Date');
        $perPage = 6;

        $programmes = Programme::published()
            ->when($request->input('q'), fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->when($request->input('age') && $request->input('age') !== 'Tous', function ($q) use ($request) {
                $map = ['Adolescents' => 'adolescents', 'Adultes' => 'adultes', 'Entreprises' => 'entreprises'];
                $q->where('audience', $map[$request->input('age')] ?? '');
            })
            ->when($request->input('type') && $request->input('type') !== 'Tous', function ($q) use ($request) {
                $map = ['Individuel' => 'individual', 'Groupe' => 'group', 'Entreprise' => 'corporate'];
                $q->where('type', $map[$request->input('type')] ?? '');
            })
            ->when($sort === 'Prix', fn ($q) => $q->orderByRaw('price_amount IS NULL, price_amount asc'))
            ->when($sort !== 'Prix', fn ($q) => $q->orderBy('start_date'))
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Programmes/Index', [
            'programmes' => $programmes,
            'filters' => $request->only(['q', 'age', 'type', 'sort']),
        ]);
    }

    public function show(Programme $programme): Response
    {
        abort_unless($programme->status === 'published', 404);

        return Inertia::render('Programmes/Show', [
            'programme' => $programme,
            'testimonials' => Testimonial::featured()->orderBy('sort_order')->take(2)->get(),
            'gallery' => $programme->galleryItems()->orderBy('sort_order')->get(),
        ]);
    }
}
