<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Contact', [
            'testimonials' => Testimonial::featured()->orderBy('sort_order')->get(),
        ]);
    }
}
