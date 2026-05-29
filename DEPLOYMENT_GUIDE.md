# 🚀 Guide de Déploiement - Gestion Finance sur Colify

## Vue d'ensemble

Ce projet est maintenant préparé pour un déploiement Docker sur **Colify** avec:
- ✅ Dockerfile optimisé (multi-stage, production-ready)
- ✅ docker-compose.yml pour déploiement local/développement
- ✅ Configuration Nginx + Supervisor + PHP-FPM
- ✅ Gestion automatique des migrations et queue
- ✅ Scripts de déploiement automatisé

---

## 📁 Fichiers créés

```
gestion-finance/
├── Dockerfile                    # Image Docker production
├── docker-compose.yml            # Orchestration services (MySQL)
├── docker-compose.sqlite.yml     # Alternative avec SQLite
├── .dockerignore                 # Exclusions pour le build
├── .env.production               # Variables d'env production
├── colify.toml                   # Configuration Colify
├── deploy.sh                     # Script déploiement (Linux/Mac)
├── deploy.ps1                    # Script déploiement (Windows)
├── DOCKER_DEPLOYMENT.md          # Doc complète
├── DEPLOYMENT_GUIDE.md           # Ce fichier
└── docker/
    ├── nginx.conf                # Configuration Nginx
    ├── default.conf              # VHost Nginx
    └── supervisord.conf          # Configuration Supervisor
```

---

## 🎯 Déploiement sur Colify - Étapes rapides

### Étape 1: Préparer le repository

```bash
cd gestion-finance

# Vérifier les fichiers créés
git status

# Ajouter les fichiers Docker
git add Dockerfile docker-compose.yml .dockerignore docker/ .env.production colify.toml deploy.* DOCKER_DEPLOYMENT.md DEPLOYMENT_GUIDE.md

# Commit
git commit -m "chore: add Docker & Colify deployment configuration"

# Push
git push origin main
```

### Étape 2: Créer un projet sur Colify

1. Aller sur **https://colify.io** (ou votre instance Colify)
2. Créer un nouveau projet
3. Connecter votre repository GitHub/GitLab
4. Sélectionner la branche `main`

### Étape 3: Configurer les variables d'environnement

Dans le dashboard Colify, ajouter les variables:

| Variable | Valeur | Notes |
|----------|--------|-------|
| `APP_NAME` | GestionFinance | Nom de l'app |
| `APP_ENV` | production | Mode production |
| `APP_DEBUG` | false | Désactiver debug |
| `APP_URL` | https://app.colify.io | Domaine final (ou custom) |
| `APP_KEY` | (auto-généré) | Sera généré lors du déploiement |
| `DB_CONNECTION` | mysql | Ou postgres |
| `DB_HOST` | (fourni par Colify) | Si BD gérée par Colify |
| `DB_DATABASE` | gestion_finance | Nom BD |
| `DB_USERNAME` | laravel | Utilisateur BD |
| `DB_PASSWORD` | (mot de passe sécurisé) | ⚠️ Utiliser un mot de passe fort |
| `LOG_CHANNEL` | stack | Logs |
| `QUEUE_CONNECTION` | database | Queue |
| `CACHE_STORE` | database | Cache |

### Étape 4: Configurer la base de données

**Option A: BD gérée par Colify**
1. Ajouter un service MySQL/PostgreSQL depuis le dashboard
2. Colify injectera automatiquement `DB_HOST`, `DB_PORT`, etc.

**Option B: BD externe**
1. Utiliser une BD externe (Heroku, AWS RDS, etc.)
2. Configurer `DB_HOST`, `DB_PORT`, etc. manuellement

**Option C: SQLite (développement)**
1. Utiliser `docker-compose.sqlite.yml` pour dev
2. En production: préférer MySQL/PostgreSQL

### Étape 5: Déployer

1. Retourner au dashboard Colify
2. Cliquer sur **"Deploy"** ou **"Redeploy"**
3. Attendre la construction de l'image (~5-10 min)
4. Une fois déployé, l'app sera accessible via l'URL fournie par Colify

---

## 🧪 Test local avant déploiement

Avant de déployer sur Colify, testez localement:

### Avec MySQL
```bash
docker-compose up -d
```

### Avec SQLite
```bash
docker-compose -f docker-compose.sqlite.yml up -d
```

### Vérifier le déploiement
```bash
# Voir les logs
docker-compose logs -f app

# Vérifier la santé
curl http://localhost/health

# Accéder au shell
docker-compose exec app sh

# Tester les migrations
docker-compose exec app php artisan migrate:status
```

---

## 📊 Architecture déployée

```
┌─────────────────────────────────┐
│      Colify Load Balancer       │
│      (auto SSL/TLS)             │
└────────────────┬────────────────┘
                 │
        ┌────────▼────────┐
        │   Nginx (Port 80)    │
        │   (Reverse Proxy)    │
        └────────┬────────┘
                 │
      ┌──────────┴──────────┐
      │   PHP-FPM (9000)    │
      │   - App Code        │
      │   - Queue Worker    │
      │   - Scheduler       │
      └──────────┬──────────┘
                 │
        ┌────────▼────────┐
        │  MySQL Database  │
        │  (Persistent)    │
        └──────────────────┘
```

---

## 🔑 Variables clés

### APP_KEY (Application Encryption Key)
Doit être unique par environnement:
```bash
# Généré automatiquement par Colify, MAIS vous pouvez:
docker-compose exec app php artisan key:generate
```

### APP_URL
Doit correspondre à votre domaine final:
```env
# Dev local
APP_URL=http://localhost

# Production Colify
APP_URL=https://gestion-finance.colify.io
# Ou domaine custom
APP_URL=https://app.monentreprise.com
```

---

## 🔐 Sécurité - Checklist

- [ ] `APP_DEBUG=false` en production
- [ ] `APP_KEY` unique et sécurisé (auto-généré)
- [ ] `DB_PASSWORD` fort et unique
- [ ] SSL/TLS configuré (Colify le gère automatiquement)
- [ ] Logs stockés et centralisés
- [ ] Backups BD activés (dans Colify ou externalisés)
- [ ] Secrets stockés via Colify (pas en dur dans .env)

---

## 🛠️ Troubleshooting

### "Erreur de connexion à la base de données"
```bash
# Vérifier que la BD est running
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

### "Application timeout"
Augmenter `startup_period` dans docker-compose.yml ou dans Colify:
```yaml
healthcheck:
  start_period: 60s  # Au lieu de 40s
```

### "Permission denied on storage/"
```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose restart
```

### "Port 80 déjà utilisé"
```bash
# Changer le port
docker-compose up -d -e APP_PORT=8080
```

---

## 📈 Monitoring & Performance

### Logs en production
```bash
# Via Colify dashboard ou SSH:
docker-compose logs -f app

# Ou accéder directement aux fichiers:
docker-compose exec app tail -f storage/logs/laravel.log
```

### Métriques
- Colify fournit un dashboard avec CPU, RAM, disk, requests/sec
- Activer les logs structurés pour une meilleure observabilité

---

## 🚄 Optimisations après déploiement

```bash
# Cache les routes
docker-compose exec app php artisan route:cache

# Cache la configuration
docker-compose exec app php artisan config:cache

# Optimiser l'autoloader Composer
docker-compose build --build-arg COMPOSER_ARGS="--no-dev --optimize-autoloader"
```

---

## 📚 Ressources

- **Dockerfile Reference**: https://docs.docker.com/engine/reference/builder/
- **Laravel Documentation**: https://laravel.com/docs
- **Colify Docs**: https://docs.colify.io/
- **Docker Compose**: https://docs.docker.com/compose/

---

## 💬 Support

- Logs complets: `docker-compose logs app`
- Health check: `curl http://localhost/health`
- Shell access: `docker-compose exec app sh`
- Artisan commands: `docker-compose exec app php artisan`

---

**Dernière mise à jour**: 2024-05-29  
**Status**: ✅ Prêt pour déploiement Colify
