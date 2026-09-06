<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'warning' => fn () => $request->session()->get('warning'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'content' => [
                'heroTitle' => setting('hero_title', "J'aide les ados, les leaders et les entreprises à se reconnecter, à croire en eux et oser"),
                'tagline' => setting('tagline', 'Un potentiel pour chaque personne, une valeur sûre pour l’Afrique.'),
                'ctaTitle' => setting('cta_title', "Commence dès aujourd'hui"),
                'ctaText' => setting('cta_text', 'Assez réfléchi. Il est temps d’agir. Réserve ta première séance dès maintenant et commence ta transformation.'),
                'email' => setting('contact_email', 'contact@stephane-ouattara.com'),
                'phone' => setting('contact_phone', '+225 07 48 78 81 33'),
                'hours' => setting('contact_hours', 'Lundi – Vendredi : 9h00 – 18h00'),
                'hours2' => setting('contact_hours_2', 'Samedi : sur rendez-vous'),
                'address' => setting('contact_address', 'Cabinet OMSY EDUC, Cocody, Abidjan, Côte d’Ivoire'),
                'coverage' => setting('contact_coverage', 'Présentiel et en ligne — toute la Côte d’Ivoire et à l’international'),
                'bio' => setting('footer_bio', "Stéphane OUATTARA est coach certifié en développement personnel, entrepreneur social et expert en autonomisation des jeunes. Fort de plus de 10 ans d'expérience, il accompagne jeunes, institutions et communautés vers l'impact durable."),
                'heroImage' => $this->imageUrl(setting('hero_image_path')),
                'coachImage' => $this->imageUrl(setting('coach_image_path')),
            ],
        ];
    }

    private function imageUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
