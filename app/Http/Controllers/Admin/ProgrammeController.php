<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgrammeRequest;
use App\Models\Programme;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProgrammeController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Programmes/Index', [
            'programmes' => Programme::query()
                ->withCount(['bookings as confirmed_count' => fn ($q) => $q->whereIn('status', ['confirmed', 'completed'])])
                ->when($request->input('search'), fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
                ->orderByDesc('created_at')
                ->paginate(10)
                ->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Programmes/Create');
    }

    public function store(StoreProgrammeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->images->store($request->file('image'), 'programmes');
        }

        Programme::create($data + ['created_by' => $request->user()->id]);

        return redirect()->route('admin.programmes.index')->with('success', 'Programme créé.');
    }

    public function edit(Programme $programme): Response
    {
        return Inertia::render('Admin/Programmes/Edit', ['programme' => $programme]);
    }

    public function update(StoreProgrammeRequest $request, Programme $programme): RedirectResponse
    {
        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->images->replace($programme->image_path, $request->file('image'), 'programmes');
        }

        $programme->update($data);

        return redirect()->route('admin.programmes.index')->with('success', 'Programme mis à jour.');
    }

    public function destroy(Programme $programme): RedirectResponse
    {
        $programme->delete();

        return back()->with('success', 'Programme archivé.');
    }
}
