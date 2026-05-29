#!/bin/sh
set -e

echo "🚀 Initialisation Gestion Finance..."

cd /app

# Laravel lit toute sa configuration depuis les variables d'environnement
# injectées par docker-compose (safeLoad ignore l'absence de .env).
# On crée un .env vide uniquement pour éviter d'éventuels warnings.
if [ ! -f /app/.env ]; then
    touch /app/.env
fi

# Vider tout cache config/route/view résiduel (fichiers commités ou volume stale)
# pour que Laravel lise les vraies variables d'environnement
php artisan config:clear  2>/dev/null || true
php artisan cache:clear   2>/dev/null || true

# Fixer les permissions sur storage/ (peut être root-owned sur les bind mounts Coolify)
chown -R www-data:www-data /app/storage /app/bootstrap/cache 2>/dev/null || true

# ------------------------------------------------------------
# Attendre que MySQL accepte les connexions (au-delà du healthcheck)
# ------------------------------------------------------------
cat > /tmp/dbcheck.php <<'PHP'
<?php
$h  = getenv('DB_HOST')     ?: 'db';
$p  = getenv('DB_PORT')     ?: '3306';
$u  = getenv('DB_USERNAME') ?: 'root';
$pw = getenv('DB_PASSWORD') ?: '';
$db = getenv('DB_DATABASE') ?: '';
try {
    new PDO("mysql:host=$h;port=$p;dbname=$db", $u, $pw, [PDO::ATTR_TIMEOUT => 2]);
    exit(0);
} catch (Throwable $e) {
    exit(1);
}
PHP

echo "⏳ Attente de la base de données MySQL..."
ATTEMPTS=0
until php /tmp/dbcheck.php 2>/dev/null; do
    ATTEMPTS=$((ATTEMPTS + 1))
    if [ "$ATTEMPTS" -ge 30 ]; then
        echo "❌ Impossible de se connecter à MySQL après 30 tentatives"
        exit 1
    fi
    echo "   ... MySQL pas encore prêt (tentative $ATTEMPTS/30)"
    sleep 2
done
echo "✅ MySQL est prêt!"

# ------------------------------------------------------------
# Migrations
# ------------------------------------------------------------
echo "🗄️  Exécution des migrations..."
php artisan migrate --force --no-interaction

# ------------------------------------------------------------
# Optimisations production (non bloquantes)
# ------------------------------------------------------------
echo "⚡ Optimisation (config, routes, vues)..."
php artisan config:cache || echo "⚠️  config:cache ignoré"
php artisan route:cache  || echo "⚠️  route:cache ignoré"
php artisan view:cache   || echo "⚠️  view:cache ignoré"
php artisan storage:link 2>/dev/null || true

echo "✨ Initialisation terminée — démarrage des services..."

# Démarrer Nginx + PHP-FPM + Queue + Scheduler via Supervisor
exec supervisord -c /etc/supervisord.conf
