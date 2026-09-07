<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\Testimonial;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ContentController extends Controller
{
    public function __construct(private ImageService $images) {}

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
            'images' => [
                'hero_image' => $this->imageUrl(setting('hero_image_path')),
                'coach_image' => $this->imageUrl(setting('coach_image_path')),
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

    public function updateImages(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'coach_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('hero_image')) {
            set_setting('hero_image_path', $this->images->replace(setting('hero_image_path'), $validated['hero_image'], 'site'));
        }

        if ($request->hasFile('coach_image')) {
            set_setting('coach_image_path', $this->images->replace(setting('coach_image_path'), $validated['coach_image'], 'site'));
        }

        return back()->with('success', 'Images mises à jour.');
    }

    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:2000'],
            'featured' => ['boolean'],
        ]);

        Testimonial::create($validated + ['sort_order' => $this->nextSortOrder(Testimonial::class)]);

        return back()->with('success', 'Témoignage ajouté.');
    }

    public function destroyTestimonial(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return back()->with('success', 'Témoignage supprimé.');
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

    public function storeGallery(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'slot_label' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $validated['image_path'] = $this->images->store($request->file('image'), 'gallery');
        unset($validated['image']);

        GalleryItem::create($validated + ['sort_order' => $this->nextSortOrder(GalleryItem::class)]);

        return back()->with('success', 'Image ajoutée à la galerie.');
    }

    public function destroyGallery(GalleryItem $gallery): RedirectResponse
    {
        // Drop the file too, otherwise the volume keeps growing with orphans.
        $this->images->delete($gallery->image_path);
        $gallery->delete();

        return back()->with('success', 'Élément de galerie supprimé.');
    }

    public function updateGallery(Request $request, GalleryItem $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'slot_label' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $this->images->replace($gallery->image_path, $validated['image'], 'gallery');
        }
        unset($validated['image']);

        $gallery->update($validated);

        return back()->with('success', 'Élément de galerie mis à jour.');
    }

    /** New entries land at the end of the public list. */
    private function nextSortOrder(string $model): int
    {
        return ((int) $model::max('sort_order')) + 1;
    }

    private function imageUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
