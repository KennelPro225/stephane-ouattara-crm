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
        $sort = $request->input('sort', 'date_asc');

        $programmes = Programme::published()
            ->when($request->input('search'), fn ($q, $search) => $q->where(
                fn ($qq) => $qq->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
            ))
            // Public : adolescents (mineurs), adultes (18+), entreprises (type corporate)
            ->when($request->input('audience'), function ($q, $audience) {
                if ($audience === 'adolescents') {
                    $q->where('age_max', '<=', 25)->whereNot('type', 'corporate');
                } elseif ($audience === 'adultes') {
                    $q->where('age_min', '<=', 60)->whereNotIn('type', ['corporate']);
                } elseif ($audience === 'entreprises') {
                    $q->whereIn('type', ['corporate']);
                }
            })
            ->when($request->input('type'), fn ($q, $type) => $q->where('type', $type))
            ->when($sort === 'date_desc', fn ($q) => $q->orderByDesc('start_date'))
            ->when($sort === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when(! in_array($sort, ['date_desc', 'price_asc', 'price_desc']), fn ($q) => $q->orderBy('start_date'))
            ->paginate(9)
            ->withQueryString();

        return Inertia::render('Programmes/Index', [
            'programmes' => $programmes,
            'filters' => $request->only(['search', 'audience', 'type', 'sort']),
        ]);
    }

    public function show(string $slug): Response
    {
        $programme = Programme::published()->where('slug', $slug)->firstOrFail();

        return Inertia::render('Programmes/Show', [
            'programme' => $programme,
            'testimonials' => Testimonial::where('approved', true)
                ->where(function ($q) use ($programme) {
                    $q->where('programme_id', $programme->id)
                        ->orWhereNull('programme_id');
                })
                ->latest()
                ->take(6)
                ->get(),
        ]);
    }
}
