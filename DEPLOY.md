# Déploiement en production

Image unique et autonome : **nginx + php-fpm + worker de file d'attente** pilotés par
supervisord. Le CRM tourne sur SQLite, donc un seul conteneur — il n'y a rien à
répartir sur plusieurs instances.

La cible de production est **Railway** (section ci-dessous). Le `docker-compose.yml`
sert au test local et à un auto-hébergement éventuel.

---

# Railway (production)

Railway construit l'image depuis le `Dockerfile`, termine le TLS et fournit le
domaine public : il n'y a ni reverse proxy ni certificat à gérer.

## 1. Créer le service

Connecte le dépôt à un projet Railway. `railway.json` fixe déjà le builder
Dockerfile, le health check sur `/up` et **`numReplicas: 1`** — ne l'augmente
pas : SQLite ne supporte pas plusieurs instances écrivant en parallèle.

## 2. Attacher le volume — indispensable

Railway n'autorise **qu'un seul volume par service**, monté sur **`/data`**.
Tout l'état persistant y vit :

| Chemin | Contenu |
|---|---|
| `/data/database.sqlite` | Base : clients, réservations, programmes, réglages |
| `/data/uploads` | Images des programmes, galerie, site (`storage/app/public` y pointe) |
| `/data/app_key` | Clé applicative générée au premier démarrage |

Sans ce volume, **tout est perdu à chaque redéploiement** : le conteneur
démarrerait quand même, avec une base vide et une nouvelle clé.

## 3. Variables d'environnement

À définir dans l'onglet Variables du service :

```
APP_NAME=Stéphane Ouattara
APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
LOG_LEVEL=warning
SESSION_SECURE_COOKIE=true

MAIL_MAILER=smtp
MAIL_HOST=…
MAIL_PORT=587
MAIL_USERNAME=…
MAIL_PASSWORD=…
MAIL_FROM_ADDRESS=contact@stephane-ouattara.com
```

Ce qu'il ne faut **pas** définir, c'est déjà géré :

- `PORT` — injecté par Railway ; nginx est configuré dessus au démarrage.
- `APP_URL` — déduit de `RAILWAY_PUBLIC_DOMAIN`. Sur un domaine personnalisé,
  définis-le explicitement (`https://stephane-ouattara.com`).
- `APP_KEY` — générée et conservée dans `/data/app_key`.
- `DB_CONNECTION` / `DB_DATABASE`, `APP_ENV`, `APP_DEBUG` — valeurs par défaut
  de l'image.

## 4. Déployer

Au démarrage, les logs affichent l'adresse publique retenue :

```
[entrypoint] env=production debug=false db=sqlite mailer=smtp port=8080
[entrypoint] public address: https://<ton-service>.up.railway.app
```

Les migrations sont jouées à chaque démarrage : un simple `git push` suffit
pour livrer une mise à jour.

## 5. Compte administrateur

⚠️ Le site est public : **le compte de démonstration
`admin@stephane-ouattara.com` / `Champion2026!` figure en clair dans le seeder
du dépôt**. Tant qu'il est en place, n'importe qui peut accéder à
l'administration. Pour changer le mot de passe :

```sh
railway run php artisan tinker --execute="
App\Models\User::where('email','admin@stephane-ouattara.com')
    ->update(['password' => bcrypt('UN_MOT_DE_PASSE_SOLIDE')]);"
```

Charger les données de démonstration (12 programmes, témoignages, galerie) :
`railway run php artisan db:seed --force`.

---

# Auto-hébergement (Docker Compose)

## Démarrage

```sh
cp .env.docker.example .env.docker
# Renseigne APP_URL (l'URL publique en https) et les identifiants SMTP
docker compose up -d --build
```

`APP_KEY` peut rester vide : une clé est générée au premier démarrage et
conservée dans `/data/app_key`, sur le volume de la base. Elle survit donc aux
redémarrages et aux mises à jour, et ne disparaît qu'avec les données qu'elle
chiffre. Pour la gérer toi-même, renseigne `APP_KEY` — une valeur explicite
prime toujours sur la clé stockée.

L'application écoute sur `http://localhost:8090`. Si ce port est déjà pris sur
la machine : `APP_PORT=8091 docker compose up -d` (voir Dépannage).

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
| `crm-data` | `/data` | Tout l'état persistant : `database.sqlite`, `uploads/` et `app_key` |

Sauvegarde de la base :

```sh
docker compose exec app sqlite3 /data/database.sqlite ".backup '/data/backup.sqlite'" \
  && docker compose cp app:/data/backup.sqlite ./backup-$(date +%F).sqlite
```

> `sqlite3` en ligne de commande n'est pas installé dans l'image (seule
> l'extension PHP l'est). Si tu veux cette commande telle quelle, ajoute
> `sqlite` à la liste `apk add` du Dockerfile ; sinon copie simplement le
> fichier conteneur arrêté : `docker compose cp app:/data/database.sqlite .`.

## Exposition publique avec HTTPS (auto-hébergement)

Le profil `prod` ajoute **Caddy** en frontal : il occupe les ports 80/443 et
obtient un certificat Let's Encrypt automatiquement.

```sh
# .env.docker : APP_DOMAIN, ACME_EMAIL, et APP_URL=https://<domaine>
docker compose --profile prod up -d
```

Prérequis : l'enregistrement DNS A/AAAA du domaine pointe déjà vers l'IP
publique du serveur, et les ports 80 et 443 sont joignables depuis Internet —
sinon la validation Let's Encrypt échoue et Caddy réessaie en boucle.

Le port de l'application (`8090`) reste volontairement lié à la **loopback** :
Caddy l'atteint par le réseau interne, et rien ne contourne le TLS. Pour y
accéder depuis le LAN en local : `APP_BIND=0.0.0.0 docker compose up -d`.

`trustProxies` est déjà activé dans `bootstrap/app.php` — sans lui, Laravel
lirait l'adresse du proxy au lieu de celle du visiteur, générerait des liens en
`http://` et perdrait les cookies de session sécurisés.

## Exploitation courante

```sh
docker compose logs -f app          # nginx, php-fpm et le worker
docker compose restart app          # redémarrage à chaud
docker compose up -d --build        # déployer une nouvelle version
docker compose exec app php artisan queue:failed   # jobs en échec
```

Les migrations sont rejouées automatiquement à chaque démarrage, donc un
`up -d --build` suffit pour livrer une mise à jour.

## Dépannage

**« Bind for 0.0.0.0:XXXX failed: port is already allocated »** — le conteneur
est créé mais ne démarre jamais, donc Laravel n'a même pas l'occasion de
booter. Ce n'est pas un problème applicatif : un autre service occupe déjà le
port sur l'hôte. Identifier le coupable puis choisir un autre port :

```sh
docker ps --format 'table {{.Names}}\t{{.Ports}}'   # souvent un autre projet
ss -ltn | grep ':8090'                              # ou un service hôte

APP_PORT=8091 docker compose up -d
```

Le port n'a d'importance qu'en local : en production, le reverse proxy pointe
vers ce port et c'est lui qui expose 80/443.

**Le conteneur n'utilise pas le `.env` du projet — c'est voulu.** `.env` est
exclu par `.dockerignore` : y laisser des secrets les figerait dans l'image.
La configuration vient de `.env.docker` (via Compose) et la clé applicative de
`/data/app_key`. La clé de ton `.env` local ne sert qu'au développement ; celle
du conteneur est distincte, et c'est normal.

**Le conteneur redémarre en boucle en répétant le même message.**
`restart: unless-stopped` relance le conteneur tant que l'entrypoint échoue, ce
qui inonde les logs de la même erreur. Le vrai message est le premier :
`docker compose logs app | head -30`. Pour arrêter la boucle le temps du
diagnostic : `docker compose stop app`.

**Vérifier la configuration réellement chargée.** Chaque démarrage affiche une
ligne de résumé — utile quand `.env.docker` est absent ou mal nommé :

```
[entrypoint] env=production debug=false url=https://… db=sqlite mailer=smtp
```

Si `url=http://localhost` alors que tu attendais ton domaine, l'env file n'est
pas pris en compte : `docker compose config` montre ce que Compose a résolu.

**Les mails de réservation ne partent pas.** Avec `MAIL_MAILER=log` (le défaut
sans env file) ils ne sont qu'écrits dans les logs — l'entrypoint le signale au
démarrage. Vérifie aussi `docker compose exec app php artisan queue:failed`.

## Vérifié au montage de l'image

L'image a été construite et lancée réellement avant livraison : conteneur
`healthy` sans redémarrage, migrations jouées, `/`, `/programmes`,
`/reserver-une-session`, `/contact`, `/login` et `/up` en 200, bundles Vite
servis en `Cache-Control: immutable`, lien `public/storage` correct, worker
drainant une notification `ShouldQueue` sans échec, et aucun `.env`, base de
dev, `tests/` ou dépendance de dev embarqués dans l'image (283 Mo).

Les trois scénarios de clé ont été testés : `APP_KEY` vide (générée puis
identique après redémarrage), `APP_KEY` explicite (prioritaire), et absence
totale de `.env.docker` (démarre sur les valeurs par défaut de l'image).

Le fonctionnement Railway a été simulé localement (`PORT=7777`,
`RAILWAY_PUBLIC_DOMAIN=…`) : nginx écoute bien sur le port injecté, le health
check passe, et les URLs générées — liens et images téléversées — utilisent
automatiquement le domaine public :

```
[entrypoint] public address: https://crm-demo.up.railway.app
URL d'une image : https://crm-demo.up.railway.app/storage/programmes/exemple.jpg
```

## Passer sur MySQL/PostgreSQL

Le code n'a aucune dépendance à SQLite. Il faut :

1. ajouter l'extension au Dockerfile (`docker-php-ext-install pdo_mysql` ou
   `pdo_pgsql`) ;
2. renseigner `DB_CONNECTION` / `DB_HOST` / `DB_DATABASE` / `DB_USERNAME` /
   `DB_PASSWORD` dans `.env.docker` ;
3. ajouter le service base de données dans `docker-compose.yml` et retirer le
   volume `crm-database`.
