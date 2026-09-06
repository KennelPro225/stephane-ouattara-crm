#!/bin/sh
set -e

APP_DIR=/var/www/html
cd "$APP_DIR"

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
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

if [ "$DB_CONNECTION" = "sqlite" ]; then
    mkdir -p "$(dirname "$DB_DATABASE")"
    [ -f "$DB_DATABASE" ] || touch "$DB_DATABASE"
fi

# Printed so a missing or wrong env file is obvious in the logs rather than
# showing up later as broken image URLs or mail that never leaves the app.
echo "[entrypoint] env=${APP_ENV:-production} debug=${APP_DEBUG:-false} url=${APP_URL:-http://localhost} db=${DB_CONNECTION} mailer=${MAIL_MAILER:-log}"
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
if [ "$DB_CONNECTION" = "sqlite" ]; then
    chown www-data:www-data "$DB_DATABASE" "$(dirname "$DB_DATABASE")"
fi

echo "[entrypoint] starting nginx, php-fpm and the queue worker"
exec "$@"
