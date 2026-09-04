<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\Testimonial;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ContentController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function index(): Response
    {
        return Inertia::render('Admin/Content', [
            'testimonials' => Testimonial::with('programme:id,title')->latest()->paginate(10),
            'galleries' => GalleryItem::with('programme:id,title')->orderBy('sort_order')->get(),
            'settings' => [
                'hero_title' => setting('hero_title', "J'aide les ados, les leaders et les entreprises à se reconnecter, à croire en eux et oser."),
                'hero_subtitle' => setting('hero_subtitle', 'Coach certifié en développement personnel — plus de 10 ans d\'expérience au service du potentiel humain en Côte d\'Ivoire.'),
                'about_text' => setting('about_text', 'Stéphane Ouattara accompagne jeunes, adultes et entreprises vers leur pleine potentialité.'),
                'contact_email' => setting('contact_email', 'contact@stephane-ouattara.com'),
                'contact_phone' => setting('contact_phone', '+225 07 48 78 81 33'),
            ],
        ]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['required', 'string', 'max:500'],
            'about_text' => ['required', 'string', 'max:2000'],
            'contact_email' => ['required', 'email'],
            'contact_phone' => ['required', 'string', 'max:30'],
        ]);

        foreach ($validated as $key => $value) {
            set_setting($key, $value);
        }

        return back()->with('success', 'Contenu mis à jour.');
    }

    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'author_title' => ['nullable', 'string', 'max:255'],
            'author_company' => ['nullable', 'string', 'max:255'],
            'programme_id' => ['nullable', 'exists:programmes,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'featured' => ['boolean'],
            'approved' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $this->images->store($request->file('image'), 'testimonials');
        }
        unset($validated['image']);

        Testimonial::create($validated);

        return back()->with('success', 'Témoignage ajouté.');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'author_title' => ['nullable', 'string', 'max:255'],
            'author_company' => ['nullable', 'string', 'max:255'],
            'programme_id' => ['nullable', 'exists:programmes,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'featured' => ['boolean'],
            'approved' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $this->images->replace($testimonial->image_path, $request->file('image'), 'testimonials');
        }
        unset($validated['image']);

        $testimonial->update($validated);

        return back()->with('success', 'Témoignage mis à jour.');
    }

    public function destroyTestimonial(Testimonial $testimonial)
    {
        $this->images->delete($testimonial->image_path);
        $testimonial->delete();

        return back()->with('success', 'Témoignage supprimé.');
    }

    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['required', 'string', 'max:100'],
            'caption' => ['nullable', 'string', 'max:255'],
            'media' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,mp4,webm', 'max:10240'],
            'media_type' => ['required', Rule::in(['image', 'video'])],
            'programme_id' => ['nullable', 'exists:programmes,id'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $directory = $validated['media_type'] === 'video' ? 'galleries/videos' : 'galleries';
        $validated['media_path'] = $this->images->store($request->file('media'), $directory);
        unset($validated['media']);

        GalleryItem::create($validated);

        return back()->with('success', 'Média ajouté à la galerie.');
    }

    public function destroyGallery(GalleryItem $gallery)
    {
        $this->images->delete($gallery->media_path);
        $gallery->delete();

        return back()->with('success', 'Média retiré de la galerie.');
    }
}
