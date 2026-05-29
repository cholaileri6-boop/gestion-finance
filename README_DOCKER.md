# 🐳 Gestion Finance - Déploiement Docker

## ✨ Démarrage en 1 commande

```bash
docker-compose up -d
```

**Tout est automatiquement configuré. Aucune configuration supplémentaire requise!**

### Résultat:
- ✅ Base de données MySQL créée et initialisée
- ✅ Application Laravel accessible
- ✅ Migrations exécutées
- ✅ APP_KEY généré automatiquement
- ✅ Nginx, PHP-FPM, Queue, Scheduler en cours d'exécution

---

## 📱 Accès

| Service | URL/Accès |
|---------|-----------|
| Application | `http://localhost` |
| MySQL | `localhost:3306` |
| Logs en temps réel | `docker-compose logs -f app` |

---

## 🔐 Credentials par défaut

| Variable | Valeur |
|----------|--------|
| **DB User** | `gestion` |
| **DB Password** | `GestionFinance2024!Secure` |
| **DB Name** | `gestion_finance` |
| **MySQL Root Password** | `RootPassword2024!Secure` |

⚠️ **Pour la production**: Changez ces valeurs dans `docker-compose.yml`

---

## 🛑 Arrêter / Redémarrer

```bash
# Arrêter les services (données persistent)
docker-compose down

# Redémarrer après arrêt
docker-compose up -d

# Arrêter et supprimer les données
docker-compose down -v
```

---

## 📋 Commandes utiles

```bash
# Voir l'état des services
docker-compose ps

# Voir les logs en temps réel
docker-compose logs -f app

# Accéder au shell de l'app
docker-compose exec app sh

# Exécuter des commandes Artisan
docker-compose exec app php artisan <command>

# Exemples:
docker-compose exec app php artisan tinker
docker-compose exec app php artisan migrate:status
docker-compose exec app php artisan db:seed
```

---

## 🔄 Mise à jour du code

Si vous mettez à jour le code:

```bash
# Reconstruire l'image
docker-compose build --no-cache

# Redémarrer les services
docker-compose up -d
```

Les migrations s'exécuteront automatiquement.

---

## 🚀 Déploiement sur Colify

### Étape 1: Préparer le code
```bash
git add .
git commit -m "chore: Docker configuration"
git push origin main
```

### Étape 2: Sur Colify
1. Créer un nouveau projet
2. Connecter le repository
3. Colify détectera automatiquement le Dockerfile
4. Configurer les variables (optionnel):
   - `APP_URL` = votre domaine

### Étape 3: Deploy
Cliquer sur "Deploy" - Colify buildra et lancera automatiquement!

---

## 📚 Documentation complète

Pour des configurations avancées:
- `QUICKSTART.md` - Démarrage rapide
- `DOCKER_DEPLOYMENT.md` - Guide complet Docker
- `DEPLOYMENT_GUIDE.md` - Déploiement Colify détaillé

---

**Status**: ✅ Zero-config - Tout fonctionne d'emblée!
