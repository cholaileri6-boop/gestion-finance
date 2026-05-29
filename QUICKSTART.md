# ⚡ Démarrage ultra-rapide - Gestion Finance

## 🎯 Une seule commande pour tout déployer

```bash
docker-compose up -d
```

**C'est tout!** Tout est automatiquement configuré:
- ✅ Base de données MySQL créée et initialisée
- ✅ APP_KEY généré automatiquement
- ✅ Migrations exécutées automatiquement
- ✅ Services démarrés (Nginx, PHP-FPM, Queue, Scheduler)
- ✅ Logs centralisés et accessibles

---

## 📱 Accès à l'application

**URL**: `http://localhost`

---

## 🔍 Vérifier que tout fonctionne

```bash
# Voir l'état des services
docker-compose ps

# Voir les logs en temps réel
docker-compose logs -f app

# Tester que l'app répond
curl http://localhost

# Accéder à une base de données (optionnel)
docker-compose exec app php artisan tinker
```

---

## 📊 Accès à la base de données (optionnel)

```bash
# Via CLI Laravel
docker-compose exec app php artisan tinker

# Via MySQL directement (tools externes)
# Host: localhost
# Port: 3306
# User: gestion
# Password: GestionFinance2024!Secure
# Database: gestion_finance
```

---

## 🛑 Arrêter l'application

```bash
docker-compose down
```

(Les données persistent dans le volume Docker)

---

## 🔄 Redémarrer après arrêt

```bash
docker-compose up -d
```

---

## 🚀 Configuration personnalisée (optionnel)

Si vous voulez changer les variables, éditez `docker-compose.yml`:

```yaml
environment:
  - APP_URL=https://votre-domaine.com
  - DB_PASSWORD=votre-mot-de-passe-custom
```

Puis redémarrez:
```bash
docker-compose restart
```

---

## ⚠️ Mots de passe par défaut

⚠️ **ATTENTION**: Les mots de passe sont en dur pour la facilité. 
En **production**, changez-les dans `docker-compose.yml`:

```yaml
# DB_PASSWORD: GestionFinance2024!Secure  → À changer
# MYSQL_PASSWORD: GestionFinance2024!Secure  → À changer
```

---

## 📚 Documentation complète

Pour des configurations avancées, voir:
- `DOCKER_DEPLOYMENT.md` - Guide complet Docker
- `DEPLOYMENT_GUIDE.md` - Guide Colify en détail

---

**Status**: ✅ Prêt à déployer - Aucune configuration requise!
