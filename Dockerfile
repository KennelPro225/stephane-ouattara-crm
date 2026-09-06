# syntax=docker/dockerfile:1

# ─────────────────────────────────────────────────────────────────────────────
# Stage 1 — front-end assets (Inertia + Vue 3 through Vite)
# ─────────────────────────────────────────────────────────────────────────────
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

# artisan is copied so laravel-vite-plugin resolves the project root reliably.
COPY vite.config.js artisan ./
COPY resources ./resources

# Emits public/build (hashed bundles) and the self-hosted Archivo fonts.
RUN npm run build


# ─────────────────────────────────────────────────────────────────────────────
# Stage 2 — PHP dependencies
# Built on the runtime's PHP version so platform requirements are checked for
# real rather than assumed.
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.4-cli-alpine AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache git unzip

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction \
        --no-progress

COPY . .

# Deferred until the app is present: the autoloader has a "files" entry for
# app/Support/helpers.php, and package:discover needs the full tree.
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative \
    && composer run-script post-autoload-dump --no-dev


# ─────────────────────────────────────────────────────────────────────────────
# Stage 3 — runtime: nginx + php-fpm + queue worker under supervisord
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.4-fpm-alpine AS runtime

# pdo_sqlite, mbstring, fileinfo and friends ship enabled in the official image.
# opcache is added for throughput, pcntl so queue:work handles signals cleanly.
RUN set -eux; \
    apk add --no-cache nginx supervisor tzdata; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers; \
    docker-php-ext-install -j"$(nproc)" opcache pcntl; \
    apk del --no-network .build-deps; \
    rm -rf /var/cache/apk/*

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-production.ini
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# nginx workers run as www-data (see nginx.conf) and need their own temp dirs.
RUN mkdir -p /var/lib/nginx/tmp /var/log/nginx /run \
    && chown -R www-data:www-data /var/lib/nginx /var/log/nginx

WORKDIR /var/www/html

# Application code plus its vendor/ directory, then the compiled front-end.
COPY --from=vendor  --chown=www-data:www-data /app        /var/www/html
COPY --from=assets  --chown=www-data:www-data /app/public /var/www/html/public

# /data holds the SQLite database; mount a volume here to make it persistent.
RUN mkdir -p /data storage/framework/cache/data storage/framework/sessions \
        storage/framework/views storage/logs storage/app/public bootstrap/cache \
    && chown -R www-data:www-data /data storage bootstrap/cache

ENV APP_ENV=production \
    APP_DEBUG=false \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/data/database.sqlite \
    LOG_CHANNEL=stderr

EXPOSE 80

# /up is the health route registered in bootstrap/app.php.
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD wget -qO- http://127.0.0.1/up >/dev/null 2>&1 || exit 1

ENTRYPOINT ["entrypoint"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
