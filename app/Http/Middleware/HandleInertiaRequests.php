<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
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
                'error' => fn () => $request->session()->get('error'),
            ],
            'settings' => [
                'contact_email' => setting('contact_email', 'contact@stephane-ouattara.com'),
                'contact_phone' => setting('contact_phone', '+225 07 48 78 81 33'),
                'hero_title' => setting('hero_title', "J'aide les ados, les leaders et les entreprises à se reconnecter, à croire en eux et oser."),
                'hero_subtitle' => setting('hero_subtitle', "Un potentiel pour chaque personne, une valeur sûre pour l'Afrique."),
                'about_text' => setting('about_text', 'Coach certifié en développement personnel, entrepreneur social et bâtisseur d\'écosystèmes : plus de 10 ans au service du potentiel humain en Côte d\'Ivoire.'),
            ],
        ];
    }
}
