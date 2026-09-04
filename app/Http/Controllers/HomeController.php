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
        return Inertia::render('Home', [
            'flagship' => Programme::published()->featured()->orderBy('start_date')->take(3)->get(),
            'testimonials' => Testimonial::featured()->orderBy('sort_order')->get(),
            'gallery' => GalleryItem::orderBy('sort_order')->get(),
            'heroStats' => [
                ['n' => '10+', 'l' => "ans d'accompagnement"],
                ['n' => '1K+', 'l' => 'participants'],
                ['n' => '50+', 'l' => 'entreprises'],
                ['n' => '200+', 'l' => 'jeunes accompagnés'],
            ],
        ]);
    }
}
