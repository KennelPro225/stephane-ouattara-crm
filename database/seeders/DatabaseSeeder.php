<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\GalleryItem;
use App\Models\Programme;
use App\Models\Session;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@stephaneouattara.ci'],
            [
                'name' => 'Stéphane Ouattara',
                'password' => bcrypt('Champion2026!'),
                'role' => 'admin',
            ]
        );

        /* -----------------------------------------------------------------
         | Programmes
         * ----------------------------------------------------------------*/
        $programmes = collect([
            [
                'title' => 'Club des Champions — Lion',
                'level' => 'Lion',
                'description' => "Programme d'excellence pour adolescents : développement personnel, leadership et confiance en soi. Les Lions forgent une identité solide, cultivent la confiance en soi et développent un leadership affirmé à travers le théâtre éducatif et des défis hebdomadaires.",
                'short_description' => 'Confiance, identité et leadership pour les plus jeunes.',
                'age_min' => 10,
                'age_max' => 13,
                'type' => 'group',
                'price' => 75000,
                'max_participants' => 20,
                'start_date' => now()->addWeeks(2)->toDateString(),
                'end_date' => now()->addMonths(4)->toDateString(),
                'featured' => true,
            ],
            [
                'title' => 'Club des Champions — Meute',
                'level' => 'Meute',
                'description' => "Niveau intermédiaire du Club des Champions : les jeunes apprennent à coopérer, communiquer et se soutenir mutuellement. Esprit de meute : on avance ensemble, on gagne ensemble.",
                'short_description' => 'Esprit d\'équipe et intelligence collective pour les 14-17 ans.',
                'age_min' => 14,
                'age_max' => 17,
                'type' => 'group',
                'price' => 90000,
                'max_participants' => 20,
                'start_date' => now()->addWeeks(3)->toDateString(),
                'end_date' => now()->addMonths(4)->toDateString(),
                'featured' => false,
            ],
            [
                'title' => 'Club des Champions — Aigle',
                'level' => 'Aigle',
                'description' => "Niveau avancé du Club des Champions : prise de parole en public, projet de vie, entrepreneuriat et mentorat personnalisé. Les Aigles prennent de la hauteur et préparent leur avenir.",
                'short_description' => 'Projet de vie et mentorat pour les 18-25 ans.',
                'age_min' => 18,
                'age_max' => 25,
                'type' => 'group',
                'price' => 120000,
                'max_participants' => 15,
                'start_date' => now()->addMonth()->toDateString(),
                'end_date' => now()->addMonths(5)->toDateString(),
                'featured' => true,
            ],
            [
                'title' => 'Compétence 360',
                'level' => null,
                'description' => "Formation intensive en leadership, gestion d'équipe et intelligence émotionnelle pour cadres et dirigeants. Un programme complet pour aligner performance et sens au sein de votre organisation.",
                'short_description' => 'Leadership, gestion d\'équipe et intelligence émotionnelle.',
                'age_min' => 25,
                'age_max' => 65,
                'type' => 'corporate',
                'price' => 450000,
                'max_participants' => 15,
                'start_date' => now()->addWeeks(4)->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
                'featured' => true,
            ],
            [
                'title' => 'La Connexion — Parents & Ados',
                'level' => null,
                'description' => "Un programme dédié aux parents souhaitant mieux communiquer avec leurs enfants adolescents : outils pratiques, gestion des émotions et restauration du lien familial.",
                'short_description' => 'Mieux communiquer avec ses ados au quotidien.',
                'age_min' => 30,
                'age_max' => 60,
                'type' => 'group',
                'price' => 60000,
                'max_participants' => 25,
                'start_date' => now()->addWeeks(5)->toDateString(),
                'end_date' => now()->addMonths(2)->toDateString(),
                'featured' => false,
            ],
            [
                'title' => 'Coaching Individuel — Adolescents',
                'level' => null,
                'description' => "Accompagnement sur mesure pour adolescents en quête d'identité : bilan complet, plan de développement personnalisé et suivi régulier avec le coach.",
                'short_description' => 'Un accompagnement 1-to-1 pour révéler le potentiel.',
                'age_min' => 10,
                'age_max' => 19,
                'type' => 'individual',
                'price' => 350000,
                'max_participants' => 8,
                'start_date' => now()->addWeeks(1)->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
                'featured' => false,
            ],
            [
                'title' => 'Teambuilding Impact',
                'level' => null,
                'description' => "Sessions immersives de cohésion d'équipe : escape game émotionnel, jeux de rôle professionnels, débriefs avec coaching ciblé. Renforcer les liens, créer du sens, stimuler la coopération.",
                'short_description' => 'Cohésion d\'équipe et performance collective.',
                'age_min' => 18,
                'age_max' => 65,
                'type' => 'corporate',
                'price' => 500000,
                'max_participants' => 40,
                'start_date' => now()->addWeeks(6)->toDateString(),
                'end_date' => now()->addWeeks(8)->toDateString(),
                'featured' => false,
            ],
            [
                'title' => 'Bien-être en Entreprise',
                'level' => null,
                'description' => "Replacer l'humain au cœur de la performance : gestion du stress & des émotions, méditation active pour dirigeants, coaching des managers vers un leadership apaisé, prévention du burn-out.",
                'short_description' => 'Performance et bien-être ne sont pas incompatibles.',
                'age_min' => 22,
                'age_max' => 65,
                'type' => 'corporate',
                'price' => 400000,
                'max_participants' => 30,
                'start_date' => now()->addMonth()->toDateString(),
                'end_date' => now()->addMonths(6)->toDateString(),
                'featured' => false,
            ],
        ])->map(fn ($data) => Programme::create($data + ['status' => 'published']));

        /* -----------------------------------------------------------------
         | Témoignages (les 8 témoignages officiels)
         * ----------------------------------------------------------------*/
        $testimonials = [
            ['Aminata K.', "Parent d'élève, Abidjan", 5, true, null, 'Le programme Club des Champions a transformé mon fils. Il est maintenant plus confiant et responsable. Un vrai changement en seulement 3 mois.'],
            ['Kouamé S.', 'Directeur RH, Groupe bancaire', 5, true, $programmes[6]->id ?? null, 'Les séances de teambuilding ont complètement transformé la dynamique de notre équipe. La communication est meilleure et l\'engagement est au plus haut.'],
            ['Fanta D.', 'Entrepreneure, Secteur Digital', 5, false, null, 'Le coaching m\'a permis de trouver ma voie et de lancer mon entreprise avec confiance. L\'approche de Stéphane est unique et transformatrice.'],
            ['Souleymane B.', 'Directeur Commercial, Multinationale', 5, false, $programmes[3]->id ?? null, 'Les ateliers sur le leadership ont changé ma façon de diriger. Je gère maintenant mon équipe avec plus de sérénité et d\'impact.'],
            ['Isabelle K.', "Parent d'élève, Abidjan", 5, true, $programmes[0]->id ?? null, 'Le programme « Club des Champions » a été une révélation pour mon fils. Stéphane a su éveiller en lui une motivation et une confiance que je n\'avais jamais vues auparavant. Sa méthode basée sur le théâtre éducatif est vraiment innovante et efficace.'],
            ['Dr. Sarah M.', 'DRH, Clinique internationale', 5, false, $programmes[7]->id ?? null, 'L\'approche de Stéphane en entreprise est unique. Son programme « Compétence 360 » a transformé notre culture d\'entreprise. Les exercices de teambuilding et les séances de méditation ont considérablement amélioré notre bien-être au travail.'],
            ['Antoine B.', "Chef d'entreprise et père de famille", 5, false, $programmes[4]->id ?? null, 'Grâce au programme « La Connexion », j\'ai appris à mieux communiquer avec mes enfants adolescents. Les outils et techniques partagés par Stéphane sont pratiques et facilement applicables au quotidien. Un vrai changement dans notre vie de famille.'],
            ['Marie-Claire D.', "Directrice d'école internationale", 5, false, null, 'Je recommande vivement Stéphane pour sa capacité à créer un environnement d\'apprentissage bienveillant. Son expertise en développement personnel et son expérience internationale apportent une vraie valeur ajoutée à ses interventions.'],
        ];

        foreach ($testimonials as [$name, $roleTitle, $rating, $featured, $programmeId, $message]) {
            Testimonial::create([
                'author_name' => $name,
                'author_title' => $roleTitle,
                'rating' => $rating,
                'featured' => $featured,
                'approved' => true,
                'programme_id' => $programmeId,
                'message' => $message,
            ]);
        }

        /* -----------------------------------------------------------------
         | Galerie de réalisations (placeholders SVG)
         * ----------------------------------------------------------------*/
        $galleries = [
            ['Coaching Individuel', 'Développement personnel et professionnel', 'coaching-individuel'],
            ['Ateliers de Groupe', 'Formation et développement collectif', 'ateliers-groupe'],
            ['Teambuilding', "Cohésion d'équipe et performance collective", 'teambuilding'],
            ['Conférences', "Partage d'expertise et inspiration", 'conferences'],
            ['Coaching Jeunes', 'Accompagnement spécial adolescents', 'coaching-jeunes'],
            ['Formation Leadership', 'Développement des leaders de demain', 'leadership'],
        ];

        foreach ($galleries as $i => [$title, $caption, $slug]) {
            GalleryItem::create([
                'title' => $title,
                'category' => $title,
                'caption' => $caption,
                'media_path' => "galleries/{$slug}.svg",
                'media_type' => 'image',
                'sort_order' => $i,
            ]);
        }

        /* -----------------------------------------------------------------
         | Exemples de clients & réservations
         * ----------------------------------------------------------------*/
        $customers = collect([
            ['Awa', 'Traoré', 'awa.traore@example.ci', '+225 07 01 02 03', null],
            ["Koffi", "N'Guessan", 'koffi.nguessan@example.ci', '+225 05 04 05 06', 'Ivoire Tech SARL'],
            ['Fatoumata', 'Diarra', 'fatou.diarra@example.ci', '+225 01 07 08 09', null],
            ['Jean-Marc', 'Boni', 'jm.boni@example.ci', '+225 07 10 11 12', 'Logistique Abidjan'],
        ])->map(fn ($c) => Customer::create([
            'first_name' => $c[0],
            'last_name' => $c[1],
            'email' => $c[2],
            'phone' => $c[3],
            'company' => $c[4],
            'city' => 'Abidjan',
            'source' => 'website',
            'status' => 'active',
        ]));

        Session::create([
            'customer_id' => $customers[0]->id,
            'programme_id' => $programmes[0]->id,
            'type' => 'club_des_champions',
            'preferred_date' => now()->addDays(5)->toDateString(),
            'preferred_time' => '14:00',
            'message' => 'Mon fils souhaite rejoindre le Club des Champions.',
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        Session::create([
            'customer_id' => $customers[1]->id,
            'programme_id' => $programmes[6]->id,
            'type' => 'teambuilding',
            'preferred_date' => now()->addDays(12)->toDateString(),
            'preferred_time' => '09:00',
            'message' => 'Nous sommes une équipe de 25 personnes, intéressés par le teambuilding.',
            'status' => 'pending',
        ]);
    }
}
