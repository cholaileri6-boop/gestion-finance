#!/bin/bash

echo "🚀 Démarrage Gestion Finance..."
echo ""

# Démarrer les services
docker-compose up -d

echo ""
echo "✨ Services démarrés!"
echo ""
echo "📊 État des services:"
docker-compose ps

echo ""
echo "📱 Accès à l'application:"
echo "   🌍 http://localhost"
echo ""
echo "📝 Logs en temps réel:"
echo "   docker-compose logs -f app"
echo ""
echo "🛑 Arrêter les services:"
echo "   docker-compose down"
