# Déploiement en production (Docker)

Image unique et autonome : **nginx + php-fpm + worker de file d'attente** pilotés par
supervisord. Le CRM tourne sur SQLite, donc un seul conteneur — il n'y a rien à
répartir sur plusieurs instances.

## Démarrage

```sh
cp .env.docker.example .env.docker

# Génère la clé d'application et colle-la dans APP_KEY
docker compose run --rm app php artisan key:generate --show

# Renseigne aussi APP_URL et les identifiants SMTP dans .env.docker
docker compose up -d --build
```

L'application écoute sur `http://localhost:8080`. Si le port est déjà pris :
`APP_PORT=8099 docker compose up -d`.

Au premier démarrage, l'entrypoint crée la base SQLite, joue les migrations,
refait le lien `public/storage` et met les caches de config/routes/vues en
place. Le conteneur passe `healthy` quand `/up` répond.

Créer le compte administrateur (aucun compte n'est créé automatiquement) :

```sh
docker compose exec app php artisan tinker --execute="
App\Models\User::create([
  'name' => 'Stéphane Ouattara',
  'email' => 'admin@stephane-ouattara.com',
  'password' => bcrypt('CHANGEZ_MOI'),
  'role' => 'admin',
]);"
```

Pour partir avec les données de démonstration à la place :
`docker compose exec app php artisan db:seed --force`.

## Ce que contient l'image

| Élément | Détail |
|---|---|
| Base | `php:8.4-fpm-alpine`, extensions `opcache` + `pcntl` (le reste est inclus) |
| Serveur web | nginx en frontal, php-fpm sur `127.0.0.1:9000` |
| Worker | `queue:work` — **indispensable** : les notifications de réservation implémentent `ShouldQueue`, sans lui aucun mail ne part |
| Assets | Vite compilé au build (stage `assets`), servis avec `Cache-Control: immutable` |
| Autoload | `composer install --no-dev` + autoloader classmap-authoritative |
| Santé | `HEALTHCHECK` sur la route `/up` |

Les caches (`config`, `route`, `view`, `event`) sont construits **au démarrage**,
pas au build : ils dépendent des variables d'environnement du conteneur.

## Volumes — à sauvegarder

| Volume | Chemin | Contenu |
|---|---|---|
| `crm-database` | `/data` | La base SQLite : clients, réservations, programmes, réglages |
| `crm-uploads` | `/var/www/html/storage/app/public` | Photos des programmes, galerie, images du site |

Sauvegarde de la base :

```sh
docker compose exec app sqlite3 /data/database.sqlite ".backup '/data/backup.sqlite'" \
  && docker compose cp app:/data/backup.sqlite ./backup-$(date +%F).sqlite
```

> `sqlite3` en ligne de commande n'est pas installé dans l'image (seule
> l'extension PHP l'est). Si tu veux cette commande telle quelle, ajoute
> `sqlite` à la liste `apk add` du Dockerfile ; sinon copie simplement le
> fichier conteneur arrêté : `docker compose cp app:/data/database.sqlite .`.

## Derrière un reverse proxy HTTPS

C'est le déploiement attendu (Caddy, Traefik, nginx…), avec la terminaison TLS
en amont. Deux points à ne pas oublier :

1. `APP_URL` doit être l'URL publique **en https** — les URLs des images
   téléversées en dérivent (`config/filesystems.php`).
2. Laravel ne fait confiance à aucun proxy par défaut dans ce projet. Sans ça,
   il génère des liens en `http://` et `SESSION_SECURE_COOKIE=true` casse la
   connexion. Ajoute dans `bootstrap/app.php`, dans le callback
   `withMiddleware` :

   ```php
   $middleware->trustProxies(at: '*');
   ```

   (`'*'` convient quand le proxy est le seul point d'entrée ; sinon liste les
   IP réelles.)

## Exploitation courante

```sh
docker compose logs -f app          # nginx, php-fpm et le worker
docker compose restart app          # redémarrage à chaud
docker compose up -d --build        # déployer une nouvelle version
docker compose exec app php artisan queue:failed   # jobs en échec
```

Les migrations sont rejouées automatiquement à chaque démarrage, donc un
`up -d --build` suffit pour livrer une mise à jour.

## Vérifié au montage de l'image

L'image a été construite et lancée réellement avant livraison : conteneur
`healthy`, migrations jouées, `/`, `/programmes`, `/reserver-une-session`,
`/contact`, `/login` et `/up` en 200, bundles Vite servis en
`Cache-Control: immutable`, lien `public/storage` correct, worker drainant une
notification `ShouldQueue` sans échec, et aucun `.env`, base de dev, `tests/`
ou dépendance de dev embarqués dans l'image (283 Mo).

## Passer sur MySQL/PostgreSQL

Le code n'a aucune dépendance à SQLite. Il faut :

1. ajouter l'extension au Dockerfile (`docker-php-ext-install pdo_mysql` ou
   `pdo_pgsql`) ;
2. renseigner `DB_CONNECTION` / `DB_HOST` / `DB_DATABASE` / `DB_USERNAME` /
   `DB_PASSWORD` dans `.env.docker` ;
3. ajouter le service base de données dans `docker-compose.yml` et retirer le
   volume `crm-database`.
