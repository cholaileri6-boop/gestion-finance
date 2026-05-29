# Démarrage ultra-simple pour Windows

Write-Host "🚀 Démarrage Gestion Finance..." -ForegroundColor Green
Write-Host ""

# Démarrer les services
docker-compose up -d

Write-Host ""
Write-Host "✨ Services démarrés!" -ForegroundColor Green
Write-Host ""
Write-Host "📊 État des services:" -ForegroundColor Cyan
docker-compose ps

Write-Host ""
Write-Host "📱 Accès à l'application:" -ForegroundColor Yellow
Write-Host "   🌍 http://localhost"
Write-Host ""
Write-Host "📝 Logs en temps réel:" -ForegroundColor Yellow
Write-Host "   docker-compose logs -f app"
Write-Host ""
Write-Host "🛑 Arrêter les services:" -ForegroundColor Yellow
Write-Host "   docker-compose down"
