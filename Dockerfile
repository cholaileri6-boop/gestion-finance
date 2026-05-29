# ============================================================
# Stage 1: PHP extensions + Composer dependencies
# ============================================================
FROM php:8.2-fpm-alpine AS base

# Outil qui gère automatiquement toutes les dépendances des extensions PHP
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --no-cache curl zip unzip git

# Installe toutes les extensions + leurs dépendances automatiquement
RUN install-php-extensions \
    pdo \
    pdo_sqlite \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    ctype \
    fileinfo \
    bcmath \
    xml \
    dom \
    tokenizer \
    opcache

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# 1. Installer les dépendances SANS scripts (artisan pas encore présent)
#    → couche mise en cache tant que composer.json/lock ne changent pas
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist

# 2. Copier tout le code (artisan inclus), puis régénérer l'autoloader
#    → déclenche package:discover avec artisan disponible
COPY . .
RUN composer dump-autoload --optimize --no-dev

# ============================================================
# Stage 2: Node.js — Build des assets Vite
# ============================================================
FROM node:18-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

# ============================================================
# Stage 3: Image finale (runtime uniquement)
# ============================================================
FROM php:8.2-fpm-alpine

# Outil de gestion des extensions PHP
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Librairies système runtime
RUN apk add --no-cache \
    curl \
    nginx \
    supervisor \
    openssl

# Installer les extensions PHP dans l'image finale
RUN install-php-extensions \
    pdo \
    pdo_sqlite \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    ctype \
    fileinfo \
    bcmath \
    xml \
    dom \
    tokenizer \
    opcache

WORKDIR /app

# Copier vendor Composer depuis Stage 1
COPY --from=base /app/vendor ./vendor

# Copier le code source
COPY . .

# Copier les assets compilés depuis Stage 2
COPY --from=assets /app/public/build ./public/build

# Créer les dossiers nécessaires avec les bonnes permissions
RUN mkdir -p \
        storage/logs \
        storage/app/public \
        storage/framework/views \
        storage/framework/cache \
        storage/framework/sessions \
        bootstrap/cache \
        /var/log/supervisor \
        /var/log/nginx \
        /run/nginx \
    && chown -R www-data:www-data /app \
    && chmod -R 755 storage bootstrap/cache

# Configs Nginx + Supervisor
COPY ./docker/nginx.conf       /etc/nginx/nginx.conf
COPY ./docker/default.conf     /etc/nginx/http.d/default.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf
COPY ./docker/entrypoint.sh    /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
