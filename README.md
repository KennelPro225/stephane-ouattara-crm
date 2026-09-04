# Stéphane Ouattara — Site vitrine + CRM

Site web et back-office CRM pour **Stéphane Ouattara**, coach en développement personnel et entrepreneur social à Abidjan (Côte d'Ivoire).

## Stack

- Laravel 11 · Inertia.js (Vue 3) · Tailwind CSS
- MySQL 8.0 · Authentification Laravel Breeze
- Stockage images local (`storage/app/public/` + symlink `public/storage`)
- Queue database + Scheduler pour les rappels email

## Fonctionnalités

### Site public (français)
- `/` — One-page : Hero, À propos, Services, Programmes à la une, Témoignages, CTA, formulaire de contact
- `/programmes` — Catalogue filtrable (recherche, type, tranche d'âge) avec pagination
- `/programmes/{slug}` — Fiche programme détaillée + témoignages associés
- `/reserver-une-session` — Réservation (individuel / groupe / entreprise) → crée ou met à jour le client, file un job de confirmation email
- `/adolescents` — Page « Club des Champions » (3 niveaux)
- `/entreprises` — Offres B2B, métriques ROI, témoignages corporate
- `/contact` — Coordonnées + formulaire (enregistre un lead en base)

### Back-office `/admin` (protégé par middleware `auth` + `admin`)
- Dashboard : stats (réservations, clients, conversion), dernières réservations, prochains programmes
- Programmes : CRUD complet, upload image, workflow brouillon/publié/archivé, mise en avant
- Clients : liste filtrable/recherchable, fiche détaillée avec historique des sessions, export CSV
- Analytics : graphiques Chart.js (réservations mensuelles, popularité des programmes, tunnel de conversion)
- Contenu : édition des textes de la page d'accueil + gestion des témoignages

## Installation

### Prérequis
- PHP 8.2+ avec les extensions `pdo_mysql`, `mbstring`, `openssl`, `sqlite3`
  - Ubuntu/Debian : `sudo apt-get install php8.4-mysql php8.4-sqlite3`
- Composer, Node.js 20+

### Étapes

```bash
composer install
npm install

# Base de données MySQL
mysql -u root -e "CREATE DATABASE stephane_ouattara_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
# ou via Docker :
docker run -d --name so-crm-mysql -e MYSQL_ALLOW_EMPTY_PASSWORD=yes \
  -e MYSQL_DATABASE=stephane_ouattara_crm -p 3306:3306 mysql:8.0

cp .env.example .env       # adapter DB_USERNAME / DB_PASSWORD si besoin
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

npm run build              # assets production
# ou : npm run dev         # serveur Vite pendant le développement
php artisan serve          # http://localhost:8000
```

> **Sans MySQL ?** Le projet fonctionne aussi en SQLite : dans `.env`, mettre
> `DB_CONNECTION=sqlite` puis `php artisan migrate:fresh --seed`.

### Compte admin de démonstration (seeder)

| | |
|---|---|
| URL admin | http://localhost:8000/admin/login |
| Email | `admin@stephaneouattara.ci` |
| Mot de passe | `Champion2026!` |

## Emails & tâches automatisées

- `SessionConfirmation` : envoyée au client après chaque réservation (queued)
- `AdminNewBooking` : notifie tous les admins (queued)
- `SendProgrammeReminder` : job planifié chaque jour à 08h (`routes/console.php`),
  rappelle les inscrits 1 semaine avant le début d'un programme

Pour traiter la queue : `php artisan queue:work`.
Pour le scheduler en local : `php artisan schedule:work`.
Configurer SMTP (Mailtrap/SendGrid) dans `.env` (`MAIL_*`). En local, `MAIL_MAILER=log`
écrit les emails dans `storage/logs/laravel.log`.

## Structure clé

```
app/
├── Http/Controllers/            # HomeController, ProgrammeController,
│   │                            # SessionController, PageController, ContactController
│   └── Admin/                   # Dashboard, Programme, Customer, Analytics, Content
├── Http/Requests/               # StoreProgrammeRequest, UpdateProgrammeRequest,
│                                # StoreSessionRequest, StoreCustomerRequest
├── Jobs/                        # SendBookingConfirmation, SendProgrammeReminder
├── Notifications/               # SessionConfirmation, AdminNewBooking, ProgrammeReminder
├── Services/                    # ImageService, ProgrammeService, SessionService
└── Models/                      # User, Programme, Session, Customer, Testimonial
resources/js/
├── Components/                  # Navigation, Hero, About, Services, Testimonials,
│                                # ContactForm, Footer, ProgrammeCard, Pagination,
│                                # DashboardCard, ProgrammeForm…
├── Layouts/                     # PublicLayout, AdminLayout, Guest/Authenticated
└── Pages/                       # Index, Programmes/*, Sessions/Create, Adolescents,
                                 # Companies, Contact, Admin/*
```

## Tests

```bash
php artisan test   # 28 tests (pages publiques, réservation, CRUD admin, auth)
```

Les tests tournent sur SQLite en mémoire (`phpunit.xml`) — aucun MySQL requis.
