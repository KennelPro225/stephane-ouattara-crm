<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgrammeRequest;
use App\Http\Requests\UpdateProgrammeRequest;
use App\Models\Programme;
use App\Services\ProgrammeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProgrammeController extends Controller
{
    public function __construct(private ProgrammeService $programmes) {}

    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Programmes/Index', [
            'programmes' => Programme::query()
                ->when($request->input('search'), fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
                ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
                ->orderByDesc('created_at')
                ->paginate(10)
                ->withQueryString(),
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Programmes/Create');
    }

    public function store(StoreProgrammeRequest $request)
    {
        $data = array_merge($request->validated(), ['created_by' => $request->user()->id]);
        $this->programmes->create($data, $request->file('image'));

        return redirect()->route('admin.programmes.index')->with('success', 'Programme créé avec succès.');
    }

    public function edit(Programme $programme): Response
    {
        return Inertia::render('Admin/Programmes/Edit', [
            'programme' => $programme,
        ]);
    }

    public function update(UpdateProgrammeRequest $request, Programme $programme)
    {
        $this->programmes->update($programme, $request->validated(), $request->file('image'));

        return redirect()->route('admin.programmes.index')->with('success', 'Programme mis à jour.');
    }

    public function destroy(Programme $programme)
    {
        $this->programmes->delete($programme);

        return redirect()->route('admin.programmes.index')->with('success', 'Programme supprimé.');
    }
}
