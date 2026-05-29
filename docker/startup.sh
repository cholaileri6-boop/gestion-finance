#!/bin/sh
# startup.sh — Initialisation Laravel en arrière-plan
# Exécuté par Supervisor UNE SEULE FOIS, après le démarrage de Nginx/PHP-FPM.
# Nginx répond à Traefik immédiatement ; migrations/caches tournent ici sans bloquer.

echo "⏳ [startup] Attente de MySQL..."

# Attendre que MySQL soit prêt (max 60 tentatives × 2s = 2 minutes)
i=0
until php -r "
try {
    new PDO(
        'mysql:host='  . getenv('DB_HOST') .
        ';port='       . (getenv('DB_PORT') ?: '3306') .
        ';dbname='     . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [PDO::ATTR_TIMEOUT => 3]
    );
} catch (Exception \$e) { exit(1); }
" 2>/dev/null; do
    i=$((i + 1))
    if [ "$i" -ge 60 ]; then
        echo "❌ [startup] MySQL non disponible après 120s — abandon."
        exit 1
    fi
    printf "   [%d/60] MySQL pas encore prêt, retry dans 2s...\n" "$i"
    sleep 2
done

echo "✅ [startup] MySQL disponible !"

echo "🗄️  [startup] Migrations..."
php /app/artisan migrate --force --no-interaction

echo "⚙️  [startup] Mise en cache..."
php /app/artisan config:cache  || echo "⚠️  config:cache ignoré"
php /app/artisan route:cache   || echo "⚠️  route:cache ignoré"
php /app/artisan view:cache    || echo "⚠️  view:cache ignoré"

echo "🔗 [startup] Lien symbolique storage..."
php /app/artisan storage:link --force 2>/dev/null || true

echo "✅ [startup] Initialisation terminée — démarrage des workers..."

# Démarrer queue worker et scheduler maintenant que la DB est prête
supervisorctl -c /etc/supervisord.conf start laravel-queue-worker:* || true
supervisorctl -c /etc/supervisord.conf start laravel-scheduler       || true

echo "🟢 [startup] Tout est opérationnel !"
