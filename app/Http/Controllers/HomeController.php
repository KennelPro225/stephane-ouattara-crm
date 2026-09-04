<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\Programme;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Index', [
            'featuredProgrammes' => Programme::published()->featured()->orderBy('start_date')->take(3)->get(),
            'testimonials' => Testimonial::where('approved', true)
                ->orderByDesc('featured')
                ->latest()
                ->take(8)
                ->get(),
            'galleries' => GalleryItem::orderBy('sort_order')->take(6)->get(),
        ]);
    }
}
