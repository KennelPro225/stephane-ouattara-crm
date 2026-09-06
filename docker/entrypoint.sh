#!/bin/sh
set -e

APP_DIR=/var/www/html
cd "$APP_DIR"

# Only the long-running server needs the boot sequence below. One-off commands
# (php artisan key:generate, tinker, migrate…) run straight through, so the
# image stays usable before an APP_KEY exists.
case "$1" in
    supervisord|/usr/bin/supervisord) ;;
    *) exec "$@" ;;
esac

if [ -z "${APP_KEY:-}" ]; then
    cat >&2 <<'EOF'
[entrypoint] APP_KEY is empty.

Generate one and put it in your env file, then start again:

    docker compose run --rm app php artisan key:generate --show

EOF
    exit 1
fi

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
