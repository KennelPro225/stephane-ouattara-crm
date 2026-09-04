<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\GalleryItem;
use App\Models\Programme;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@stephane-ouattara.com'],
            ['name' => 'Stéphane Ouattara', 'password' => bcrypt('Champion2026!'), 'role' => 'admin']
        );

        $programmes = collect([
            ['title' => 'Club des Champions — Niveau Lion', 'audience' => 'adolescents', 'type' => 'group', 'level' => 'Lion', 'price_amount' => 150000, 'price_label' => '150 000 FCFA', 'start_date' => '2026-10-05', 'end_date' => '2026-12-20', 'registration_deadline' => '2026-09-28', 'duration_label' => '12 semaines', 'ages_label' => '12 – 15 ans', 'max_participants' => 24, 'featured' => true, 'description' => "Premier niveau du parcours d'excellence : confiance en soi, prise de parole et discipline personnelle."],
            ['title' => 'Club des Champions — Niveau Meute', 'audience' => 'adolescents', 'type' => 'group', 'level' => 'Meute', 'price_amount' => 180000, 'price_label' => '180 000 FCFA', 'start_date' => '2026-10-12', 'end_date' => '2027-01-31', 'registration_deadline' => '2026-10-03', 'duration_label' => '16 semaines', 'ages_label' => '14 – 17 ans', 'max_participants' => 20, 'featured' => false, 'description' => 'Travail collectif, coopération et leadership partagé au sein du groupe.'],
            ['title' => 'Club des Champions — Niveau Aigle', 'audience' => 'adolescents', 'type' => 'group', 'level' => 'Aigle', 'price_amount' => 210000, 'price_label' => '210 000 FCFA', 'start_date' => '2026-11-09', 'end_date' => '2027-02-28', 'registration_deadline' => '2026-10-31', 'duration_label' => '16 semaines', 'ages_label' => '16 – 19 ans', 'max_participants' => 18, 'featured' => false, 'description' => 'Vision, projet personnel et engagement : le niveau le plus exigeant du Club.'],
            ['title' => 'Compétence 360', 'audience' => 'entreprises', 'type' => 'corporate', 'level' => 'Standard', 'price_amount' => null, 'price_label' => 'Sur devis', 'start_date' => '2026-11-02', 'end_date' => '2026-12-11', 'registration_deadline' => '2026-10-20', 'duration_label' => '6 modules', 'ages_label' => 'Adultes', 'max_participants' => 30, 'featured' => false, 'description' => 'Programme de montée en compétences 360° : posture, communication et performance collective.'],
            ['title' => 'La Connexion — Parents & Ados', 'audience' => 'adultes', 'type' => 'group', 'level' => 'Standard', 'price_amount' => 95000, 'price_label' => '95 000 FCFA', 'start_date' => '2026-10-18', 'end_date' => '2026-11-22', 'registration_deadline' => '2026-10-10', 'duration_label' => '6 séances', 'ages_label' => 'Parents', 'max_participants' => 25, 'featured' => true, 'description' => 'Rétablir le dialogue entre parents et adolescents avec des outils concrets de communication.'],
            ['title' => 'Coaching Individuel Adolescents', 'audience' => 'adolescents', 'type' => 'individual', 'level' => 'Sur mesure', 'price_amount' => 45000, 'price_label' => '45 000 FCFA / séance', 'start_date' => now()->toDateString(), 'end_date' => now()->addYear()->toDateString(), 'registration_deadline' => null, 'duration_label' => '8 séances type', 'ages_label' => '12 – 19 ans', 'max_participants' => 1, 'featured' => false, 'description' => "Un espace sur mesure pour construire identité, confiance et leadership à l'adolescence."],
            ['title' => 'Coaching Individuel Parents / Adultes', 'audience' => 'adultes', 'type' => 'individual', 'level' => 'Sur mesure', 'price_amount' => 60000, 'price_label' => '60 000 FCFA / séance', 'start_date' => now()->toDateString(), 'end_date' => now()->addYear()->toDateString(), 'registration_deadline' => null, 'duration_label' => '10 séances type', 'ages_label' => 'Adultes', 'max_participants' => 1, 'featured' => false, 'description' => 'Retrouver équilibre, clarté et épanouissement à un tournant personnel ou professionnel.'],
            ['title' => 'Ateliers Jeunes & Théâtre éducatif', 'audience' => 'adolescents', 'type' => 'group', 'level' => 'Standard', 'price_amount' => 35000, 'price_label' => '35 000 FCFA', 'start_date' => '2026-10-26', 'end_date' => '2026-11-30', 'registration_deadline' => '2026-10-18', 'duration_label' => '5 ateliers', 'ages_label' => '10 – 17 ans', 'max_participants' => 28, 'featured' => false, 'description' => 'Exprimer, ressentir, apprendre autrement : des ateliers dynamiques et bienveillants.'],
            ['title' => 'Bien-être en Entreprise', 'audience' => 'entreprises', 'type' => 'corporate', 'level' => 'Standard', 'price_amount' => null, 'price_label' => 'Sur devis', 'start_date' => '2026-11-05', 'end_date' => '2026-12-18', 'registration_deadline' => '2026-10-25', 'duration_label' => '8 séances', 'ages_label' => 'Adultes', 'max_participants' => 40, 'featured' => false, 'description' => "Respiration, méditation active et prévention du burn-out pour remettre l'humain au centre."],
            ['title' => 'Teambuilding Impact', 'audience' => 'entreprises', 'type' => 'corporate', 'level' => 'Immersif', 'price_amount' => null, 'price_label' => 'Sur devis', 'start_date' => '2026-11-14', 'end_date' => '2026-11-15', 'registration_deadline' => '2026-11-05', 'duration_label' => '2 jours', 'ages_label' => 'Adultes', 'max_participants' => 60, 'featured' => false, 'description' => 'Escape game émotionnel, jeux de rôle et débriefs coachés pour souder les équipes.'],
            ['title' => 'Formation Leadership Authentique', 'audience' => 'entreprises', 'type' => 'corporate', 'level' => 'Avancé', 'price_amount' => null, 'price_label' => 'Sur devis', 'start_date' => '2026-11-23', 'end_date' => '2027-01-29', 'registration_deadline' => '2026-11-10', 'duration_label' => '5 modules', 'ages_label' => 'Cadres', 'max_participants' => 22, 'featured' => true, 'description' => "Leadership, gestion d'équipe et intelligence émotionnelle pour cadres et dirigeants."],
            ['title' => 'Coaching de Groupe — Cercle Élévation', 'audience' => 'adultes', 'type' => 'group', 'level' => 'Restreint', 'price_amount' => 120000, 'price_label' => '120 000 FCFA', 'start_date' => '2026-12-07', 'end_date' => '2027-02-08', 'registration_deadline' => '2026-11-28', 'duration_label' => '8 séances', 'ages_label' => 'Adultes', 'max_participants' => 12, 'featured' => false, 'description' => 'Groupe restreint : partages puissants, entraide et progression collective.'],
        ])->map(fn ($data) => Programme::create($data + ['status' => 'published', 'created_by' => $admin->id]));

        $testimonials = [
            ['Aminata K.', "Parent d'élève, Abidjan", 'Le programme Club des Champions a transformé mon fils. Il est maintenant plus confiant et responsable. Un vrai changement en seulement 3 mois.'],
            ['Kouamé S.', 'Directeur RH, Groupe bancaire', "Les séances de teambuilding ont complètement transformé la dynamique de notre équipe. La communication est meilleure et l'engagement est au plus haut."],
            ['Fanta D.', 'Entrepreneure, Secteur Digital', "Le coaching m'a permis de trouver ma voie et de lancer mon entreprise avec confiance. L'approche de Stéphane est unique et transformatrice."],
            ['Souleymane B.', 'Directeur Commercial, Multinationale', "Les ateliers sur le leadership ont changé ma façon de diriger. Je gère maintenant mon équipe avec plus de sérénité et d'impact."],
            ['Isabelle K.', "Parent d'élève, Abidjan", "Le programme « Club des Champions » a été une révélation pour mon fils. Stéphane a su éveiller en lui une motivation et une confiance que je n'avais jamais vues auparavant."],
            ['Dr. Sarah M.', 'DRH, Clinique internationale', "L'approche de Stéphane en entreprise est unique. Son programme « Compétence 360 » a transformé notre culture d'entreprise."],
            ['Antoine B.', "Chef d'entreprise et père de famille", "Grâce au programme « La Connexion », j'ai appris à mieux communiquer avec mes enfants adolescents. Un vrai changement dans notre vie de famille."],
            ['Marie-Claire D.', "Directrice d'école internationale", "Je recommande vivement Stéphane pour sa capacité à créer un environnement d'apprentissage bienveillant."],
        ];
        foreach ($testimonials as $i => [$name, $role, $quote]) {
            Testimonial::create(['name' => $name, 'role' => $role, 'quote' => $quote, 'featured' => true, 'sort_order' => $i]);
        }

        $gallery = [
            ['Coaching Individuel', 'Développement personnel et professionnel', 'séance 1:1'],
            ['Ateliers de Groupe', 'Formation et développement collectif', 'atelier'],
            ['Teambuilding', "Cohésion d'équipe et performance collective", 'teambuilding'],
            ['Événements / Conférences', "Partage d'expertise et inspiration", 'conférence'],
            ['Coaching Jeunes', 'Accompagnement spécial adolescents', 'club des champions'],
            ['Formation Leadership', 'Développement des leaders de demain', 'formation'],
        ];
        foreach ($gallery as $i => [$title, $subtitle, $slot]) {
            GalleryItem::create(['title' => $title, 'subtitle' => $subtitle, 'slot_label' => $slot, 'sort_order' => $i]);
        }

        $customers = collect([
            ['Aminata', 'Koné', 'aminata.k@exemple.ci', '+225 07 11 22 33 44', null, null, 'nouveau', 'Site web'],
            ['Kouamé', 'Sery', 'k.sery@banque.ci', '+225 05 88 77 66 55', 'Groupe bancaire', 'Directeur RH', 'converti', 'Recommandation'],
            ['Fanta', 'Diallo', 'fanta@digital.ci', '+225 01 44 55 66 77', 'Studio Digital', null, 'contacte', 'Instagram'],
            ['Souleymane', 'Bamba', 's.bamba@groupe.com', '+225 07 90 12 34 56', 'Multinationale', 'Directeur Commercial', 'converti', 'Site web'],
            ['Isabelle', 'Kouassi', 'isabelle.k@exemple.ci', '+225 05 23 45 67 89', null, null, 'nouveau', 'Facebook'],
            ['Sarah', 'Mensah', 'drh@clinique.ci', '+225 27 22 33 44 55', 'Clinique internationale', 'DRH', 'converti', 'Événement'],
        ])->map(fn ($c) => Customer::create([
            'first_name' => $c[0], 'last_name' => $c[1], 'email' => $c[2], 'phone' => $c[3],
            'company' => $c[4], 'job_title' => $c[5], 'status' => $c[6], 'source' => $c[7],
        ]));

        $byTitle = fn (string $title) => $programmes->firstWhere('title', $title);

        $bookings = [
            [0, $byTitle('Club des Champions — Niveau Lion'), 'club_des_champions', now()->addDays(4), 'pending'],
            [1, $byTitle("Formation Leadership Authentique"), 'corporate_wellness', now()->addDays(6), 'confirmed'],
            [2, $byTitle('Coaching Individuel Parents / Adultes'), 'individual', now()->addDays(7), 'pending'],
            [3, $byTitle('Teambuilding Impact'), 'teambuilding', now()->addDays(41), 'confirmed'],
            [4, $byTitle('La Connexion — Parents & Ados'), 'group', now()->addDays(14), 'pending'],
            [5, $byTitle('Compétence 360'), 'corporate_wellness', now()->addDays(29), 'completed'],
        ];
        foreach ($bookings as [$ci, $programme, $type, $date, $status]) {
            Booking::create([
                'customer_id' => $customers[$ci]->id,
                'programme_id' => $programme?->id,
                'service_type' => $type,
                'preferred_date' => $date->toDateString(),
                'preferred_time' => Booking::TIME_SLOTS[array_rand(Booking::TIME_SLOTS)],
                'status' => $status,
                'confirmed_at' => in_array($status, ['confirmed', 'completed']) ? now() : null,
            ]);
        }
    }
}
