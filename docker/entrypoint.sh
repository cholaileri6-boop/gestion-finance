#!/bin/sh
set -e

echo "🚀 Démarrage de Gestion Finance..."
cd /app

# Créer .env vide si absent (Laravel utilise les OS env vars de toute façon)
[ ! -f /app/.env ] && touch /app/.env

# Supprimer le config cache stale (opération rapide, pas de DB)
php artisan config:clear 2>/dev/null || true

# Fixer les permissions storage/ (bind mounts sur Coolify = root-owned)
chown -R www-data:www-data /app/storage /app/bootstrap/cache 2>/dev/null || true

echo "▶️  Démarrage Nginx + PHP-FPM (migrations en arrière-plan)..."

# Supervisord démarre IMMÉDIATEMENT → Nginx répond à Traefik sans délai
# Le script startup.sh s'exécutera en tâche de fond via Supervisor
exec supervisord -c /etc/supervisord.conf
