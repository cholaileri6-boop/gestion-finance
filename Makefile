.PHONY: help up down logs restart shell artisan test build clean

help:
	@echo "🚀 Gestion Finance - Commandes disponibles:"
	@echo ""
	@echo "  make up              Démarrer les services"
	@echo "  make down            Arrêter les services"
	@echo "  make restart         Redémarrer les services"
	@echo "  make logs            Voir les logs en temps réel"
	@echo "  make shell           Accéder au shell de l'app"
	@echo "  make artisan [cmd]   Exécuter une commande Artisan"
	@echo "  make test            Lancer les tests"
	@echo "  make build           Rebuild l'image Docker"
	@echo "  make clean           Nettoyer les volumes"
	@echo ""

up:
	docker-compose up -d
	@echo "✅ Services démarrés!"
	@echo "🌍 Accéder à: http://localhost"

down:
	docker-compose down
	@echo "✅ Services arrêtés"

restart:
	docker-compose restart
	@echo "✅ Services redémarrés"

logs:
	docker-compose logs -f app

shell:
	docker-compose exec app sh

artisan:
	docker-compose exec app php artisan $(cmd)

test:
	docker-compose exec app php artisan test

build:
	docker-compose build --no-cache
	@echo "✅ Image reconstruite"

clean:
	docker-compose down -v
	@echo "✅ Nettoyage complet - Volumes supprimés"

ps:
	docker-compose ps

migrate:
	docker-compose exec app php artisan migrate

tinker:
	docker-compose exec app php artisan tinker

seed:
	docker-compose exec app php artisan db:seed
