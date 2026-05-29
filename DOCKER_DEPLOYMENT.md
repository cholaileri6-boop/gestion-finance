# Déploiement Docker - Gestion Finance

Ce guide explique comment déployer l'application Gestion Finance sur Colify avec Docker Compose.

## 📋 Prérequis

- Docker & Docker Compose (v3.8+)
- Au minimum 1GB de RAM disponible
- Espace disque: ~2GB pour les images et données

## 🚀 Déploiement Local

### 1. Préparation

```bash
# Cloner le projet si nécessaire
git clone <votre-repo>
cd gestion-finance

# Créer le fichier .env pour le déploiement
cp .env.example .env
# OU pour la production:
cp .env.production .env
```

### 2. Configuration de l'environnement

Éditer le fichier `.env` et configurer:

```env
# Application
APP_URL=http://localhost  # ou votre domaine
APP_ENV=production
APP_DEBUG=false

# Base de données
DB_CONNECTION=mysql
DB_HOST=db
DB_DATABASE=gestion_finance
DB_USERNAME=laravel
DB_PASSWORD=votre_mot_de_passe_securise

# Autres configurations...
```

### 3. Démarrer les services

```bash
# Build et démarrage des conteneurs
docker-compose up -d

# Vérifier l'état
docker-compose ps

# Voir les logs
docker-compose logs -f app
```

### 4. Initialiser la base de données

```bash
# Les migrations s'exécutent automatiquement au démarrage
# Mais vous pouvez aussi les exécuter manuellement:
docker-compose exec app php artisan migrate

# Créer un utilisateur admin (si applicable)
docker-compose exec app php artisan tinker
```

### 5. Accéder à l'application

- Application: `http://localhost`
- Logs: `docker-compose logs -f app`

## 🔧 Commandes utiles

```bash
# Arrêter les services
docker-compose down

# Arrêter et nettoyer les volumes
docker-compose down -v

# Reconstruire l'image
docker-compose build --no-cache

# Exécuter une commande artisan
docker-compose exec app php artisan <command>

# Voir les logs d'une service
docker-compose logs db
docker-compose logs app

# Accéder au shell du conteneur
docker-compose exec app sh
```

## 📦 Structure des services

### Service `app`
- Image: PHP 8.2 FPM + Nginx + Supervisor
- Port: 80 (configurable via `APP_PORT`)
- Services gérés par Supervisor:
  - PHP-FPM
  - Nginx
  - Queue Worker
  - Scheduler Laravel

### Service `db`
- Image: MySQL 8.0
- Port: 3306 (configurable via `DB_PORT_EXPOSE`)
- Volume persistant: `gestion-finance-db`

## 🌍 Déploiement sur Colify

### Préparation pour Colify

1. **Pousser vers Git**
   ```bash
   git add .
   git commit -m "Add Docker configuration for Colify deployment"
   git push origin main
   ```

2. **Configuration Colify**
   - Connecter votre repository GitHub/GitLab à Colify
   - Colify détectera automatiquement le `Dockerfile`
   - Configurer les variables d'environnement dans le dashboard Colify:

   ```
   APP_NAME=GestionFinance
   APP_ENV=production
   APP_KEY=base64:xxxxx (généré automatiquement)
   APP_URL=https://votre-app.colify.io
   DB_CONNECTION=mysql
   DB_HOST=<service-mysql>
   DB_DATABASE=gestion_finance
   DB_USERNAME=laravel
   DB_PASSWORD=<mot_de_passe>
   ```

3. **Base de données**
   - Ajouter un service MySQL via le dashboard Colify
   - Ou utiliser une base de données externe
   - Colify injectera automatiquement les variables `DB_HOST`, `DB_PORT`, etc.

4. **Déployer**
   - Colify buildra l'image automatiquement
   - Les migrations s'exécuteront au démarrage
   - L'application sera accessible via votre URL Colify

## 🔐 Sécurité en Production

- ✅ `APP_DEBUG=false` activé dans `.env.production`
- ✅ Logs stockés dans un volume persistant
- ✅ Base de données protégée en réseau interne
- ⚠️ **À faire:**
  - Générer une `APP_KEY` sécurisée: `php artisan key:generate`
  - Configurer les certificats SSL/TLS (Colify peut le faire automatiquement)
  - Mettre à jour les variables de base de données avec des credentials sécurisés
  - Configurer les services de mail (SMTP) en production

## 📊 Monitoring & Logs

```bash
# Afficher les derniers logs
docker-compose logs --tail=100 app

# Logs en temps réel
docker-compose logs -f app

# Logs d'une date spécifique
docker-compose logs app | grep "2024-01-15"
```

## 🆘 Troubleshooting

### Port déjà utilisé
```bash
# Changer le port dans docker-compose.yml ou via variable d'env
APP_PORT=8080 docker-compose up -d
```

### Base de données ne démarre pas
```bash
# Vérifier les logs
docker-compose logs db

# Recréer le volume
docker-compose down -v
docker-compose up -d
```

### Permissions des fichiers
```bash
# Fixer les permissions dans le conteneur
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Migrations échouent
```bash
# Vérifier la connexion à la BD
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

## 📝 Variables d'environnement importantes

| Variable | Description | Exemple |
|----------|-------------|---------|
| `APP_URL` | URL publique de l'application | `https://app.example.com` |
| `DB_CONNECTION` | Type de base de données | `mysql` ou `sqlite` |
| `DB_HOST` | Host de la BD | `db` (conteneur) |
| `MAIL_MAILER` | Service de mail | `smtp`, `log`, `mailgun` |
| `LOG_CHANNEL` | Canal de logs | `stack`, `single` |

## 📚 Documentation supplémentaire

- [Laravel Documentation](https://laravel.com/docs)
- [Docker Documentation](https://docs.docker.com/)
- [Colify Documentation](https://docs.colify.io/)

---

**Dernière mise à jour**: 2024
