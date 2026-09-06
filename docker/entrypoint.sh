#!/bin/sh
set -e

APP_DIR=/var/www/html
cd "$APP_DIR"

# ── Listening port ───────────────────────────────────────────────────────────
# Railway (and most PaaS) assign the port through $PORT and route to it, so the
# server config is rendered at boot rather than baked in. Only __PORT__ is
# substituted — nginx's own $uri/$document_root variables are left alone.
PORT="${PORT:-80}"
sed "s/__PORT__/${PORT}/g" /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# ── Public URL ───────────────────────────────────────────────────────────────
# Railway publishes the generated domain here. Uploaded image URLs and every
# generated link are built from APP_URL, so pick it up automatically instead of
# needing it hardcoded before the domain even exists.
if [ -n "${RAILWAY_PUBLIC_DOMAIN:-}" ]; then
    case "${APP_URL:-}" in
        ""|http://localhost*|https://localhost*)
            APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
            export APP_URL
            ;;
    esac
fi

# ── Application key ──────────────────────────────────────────────────────────
# An APP_KEY supplied through the environment always wins. Without one, keep a
# generated key beside the database: both live on the /data volume, so the key
# survives restarts and upgrades, and only ever disappears together with the
# data it encrypts. That makes the very first `docker compose up` just work
# instead of crash-looping on a missing key.
APP_KEY_FILE="${APP_KEY_FILE:-/data/app_key}"

if [ -z "${APP_KEY:-}" ]; then
    if [ ! -s "$APP_KEY_FILE" ]; then
        mkdir -p "$(dirname "$APP_KEY_FILE")"
        php artisan key:generate --show --no-ansi | tr -d '[:space:]' > "$APP_KEY_FILE"
        chmod 600 "$APP_KEY_FILE"
        echo "[entrypoint] no APP_KEY supplied — generated one and stored it in $APP_KEY_FILE"
    fi

    APP_KEY="$(cat "$APP_KEY_FILE")"
    export APP_KEY

    case "$APP_KEY" in
        base64:?*) ;;
        *)
            echo "[entrypoint] the key in $APP_KEY_FILE is unusable; delete it and restart." >&2
            exit 1
            ;;
    esac
fi

# Only the long-running server needs the boot sequence below. One-off commands
# (php artisan tinker, migrate, queue:failed…) run straight through — with the
# same APP_KEY, so they can read sessions and encrypted values.
case "$1" in
    supervisord|/usr/bin/supervisord) ;;
    *) exec "$@" ;;
esac

DB_CONNECTION="${DB_CONNECTION:-sqlite}"
DB_DATABASE="${DB_DATABASE:-/data/database.sqlite}"

# Volumes can shadow parts of the tree, so recreate the skeleton every boot.
mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# ── Persistent state, all under one volume ───────────────────────────────────
# Railway allows a single volume per service, so the database, the app key and
# the uploaded images all live under /data and storage/app/public becomes a
# symlink into it. One mount to attach, one thing to back up.
UPLOADS_DIR="${UPLOADS_DIR:-/data/uploads}"
mkdir -p "$UPLOADS_DIR"

if [ ! -L storage/app/public ]; then
    # First boot on an existing install: keep whatever images were already there.
    if [ -d storage/app/public ]; then
        cp -a storage/app/public/. "$UPLOADS_DIR"/ 2>/dev/null || true
        rm -rf storage/app/public
    fi
    ln -s "$UPLOADS_DIR" storage/app/public
fi

if [ "$DB_CONNECTION" = "sqlite" ]; then
    mkdir -p "$(dirname "$DB_DATABASE")"
    [ -f "$DB_DATABASE" ] || touch "$DB_DATABASE"
fi

# Printed so a missing or wrong env file is obvious in the logs rather than
# showing up later as broken image URLs or mail that never leaves the app.
echo "[entrypoint] env=${APP_ENV:-production} debug=${APP_DEBUG:-false} db=${DB_CONNECTION} mailer=${MAIL_MAILER:-log} port=${PORT}"
echo "[entrypoint] public address: ${APP_URL:-http://localhost}"
if [ "${MAIL_MAILER:-log}" = "log" ]; then
    echo "[entrypoint] note: MAIL_MAILER=log — booking confirmations are written to the log, not sent."
fi

echo "[entrypoint] running migrations…"
php artisan migrate --force --no-interaction

# Recreated rather than reused: the target is a volume that may have moved.
rm -f public/storage
php artisan storage:link --no-interaction

echo "[entrypoint] warming caches…"
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction
php artisan event:cache --no-interaction

# The commands above ran as root; hand everything writable back to the worker user.
chown -R www-data:www-data storage bootstrap/cache
chown -R www-data:www-data "$UPLOADS_DIR"
if [ "$DB_CONNECTION" = "sqlite" ]; then
    chown www-data:www-data "$DB_DATABASE" "$(dirname "$DB_DATABASE")"
fi

echo "[entrypoint] starting nginx, php-fpm and the queue worker"
exec "$@"
