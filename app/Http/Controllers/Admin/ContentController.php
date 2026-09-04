<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Contenus/Index', [
            'settings' => [
                'hero_title' => setting('hero_title'),
                'tagline' => setting('tagline'),
                'cta_title' => setting('cta_title'),
                'cta_text' => setting('cta_text'),
                'contact_email' => setting('contact_email'),
                'contact_phone' => setting('contact_phone'),
                'contact_hours' => setting('contact_hours'),
                'contact_hours_2' => setting('contact_hours_2'),
                'contact_address' => setting('contact_address'),
                'contact_coverage' => setting('contact_coverage'),
                'footer_bio' => setting('footer_bio'),
            ],
            'testimonials' => Testimonial::orderBy('sort_order')->get(),
            'gallery' => GalleryItem::orderBy('sort_order')->get(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'cta_title' => ['required', 'string', 'max:255'],
            'cta_text' => ['required', 'string', 'max:1000'],
            'contact_email' => ['required', 'email'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'contact_hours' => ['required', 'string', 'max:255'],
            'contact_hours_2' => ['required', 'string', 'max:255'],
            'contact_address' => ['required', 'string', 'max:255'],
            'contact_coverage' => ['required', 'string', 'max:255'],
            'footer_bio' => ['required', 'string', 'max:1000'],
        ]);

        foreach ($validated as $key => $value) {
            set_setting($key, $value);
        }

        return back()->with('success', 'Contenus mis à jour.');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:2000'],
            'featured' => ['boolean'],
        ]);

        $testimonial->update($validated);

        return back()->with('success', 'Témoignage mis à jour.');
    }

    public function updateGallery(Request $request, GalleryItem $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
        ]);

        $gallery->update($validated);

        return back()->with('success', 'Élément de galerie mis à jour.');
    }
}
