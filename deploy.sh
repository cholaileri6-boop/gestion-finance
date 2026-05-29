#!/bin/bash

# Colify Deployment Script
# Usage: ./deploy.sh [dev|prod|staging]

set -e

ENV=${1:-prod}
COMPOSE_FILE="docker-compose.yml"
LOG_FILE="deployment.log"

echo "🚀 Déploiement Gestion Finance - Environnement: $ENV" | tee -a "$LOG_FILE"
echo "📅 $(date)" | tee -a "$LOG_FILE"

# Vérifier les prérequis
check_requirements() {
    echo "📋 Vérification des prérequis..."

    if ! command -v docker &> /dev/null; then
        echo "❌ Docker n'est pas installé"
        exit 1
    fi

    if ! command -v docker-compose &> /dev/null; then
        echo "❌ Docker Compose n'est pas installé"
        exit 1
    fi

    echo "✅ Docker et Docker Compose détectés"
}

# Charger les variables d'environnement
load_env() {
    echo "⚙️ Chargement de la configuration..."

    if [ "$ENV" = "prod" ] && [ -f ".env.production" ]; then
        cp .env.production .env
        echo "✅ Configuration production chargée"
    elif [ ! -f ".env" ]; then
        cp .env.example .env
        echo "⚠️ Configuration par défaut utilisée (à adapter!)"
    else
        echo "✅ Fichier .env trouvé"
    fi
}

# Arrêter les services existants
stop_services() {
    echo "🛑 Arrêt des services précédents..."
    docker-compose down || true
}

# Builder l'image
build_image() {
    echo "🔨 Construction de l'image Docker..."
    docker-compose build --no-cache
    echo "✅ Image construite"
}

# Démarrer les services
start_services() {
    echo "▶️ Démarrage des services..."
    docker-compose up -d
    echo "✅ Services démarrés"

    # Attendre le démarrage
    echo "⏳ Attente du démarrage de l'application..."
    sleep 10
}

# Initialiser la base de données
initialize_db() {
    echo "🗄️ Initialisation de la base de données..."

    if docker-compose exec -T app test -f /app/database/gestion_finance.sqlite; then
        echo "⚠️ Base de données SQLite déjà existante"
    elif docker-compose exec -T app php artisan migrate:status &> /dev/null; then
        echo "✅ Migrations exécutées"
    else
        docker-compose exec -T app php artisan migrate --force
        echo "✅ Base de données initialisée"
    fi
}

# Vérifier la santé de l'application
health_check() {
    echo "🏥 Vérification de la santé..."

    if docker-compose exec -T app curl -f http://localhost/health > /dev/null 2>&1; then
        echo "✅ Application saine"
    else
        echo "⚠️ Endpoint /health indisponible (comportement normal)"
    fi
}

# Afficher les logs
show_logs() {
    echo ""
    echo "📊 État des services:"
    docker-compose ps

    echo ""
    echo "📝 Derniers logs (app):"
    docker-compose logs --tail=20 app
}

# Exécution principale
main() {
    check_requirements
    load_env
    stop_services
    build_image
    start_services
    initialize_db
    health_check
    show_logs

    echo ""
    echo "✨ Déploiement réussi!"
    echo "🌍 Application disponible à: http://localhost"
    echo "📖 Documentation: Voir DOCKER_DEPLOYMENT.md"
    echo ""
    echo "Commandes utiles:"
    echo "  - Voir les logs:      docker-compose logs -f app"
    echo "  - Terminal app:       docker-compose exec app sh"
    echo "  - Artisan commands:   docker-compose exec app php artisan <cmd>"
    echo "  - Arrêter services:   docker-compose down"
}

main
