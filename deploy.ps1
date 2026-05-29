# Colify Deployment Script for Windows
# Usage: .\deploy.ps1 -Env prod
# Or: .\deploy.ps1 -Env dev

param(
    [string]$Env = "prod",
    [switch]$SkipBuild = $false
)

$LogFile = "deployment.log"
$ComposeFile = "docker-compose.yml"

function Write-Log {
    param([string]$Message)
    Write-Host $Message
    Add-Content -Path $LogFile -Value "$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss') - $Message"
}

function Check-Requirements {
    Write-Log "📋 Vérification des prérequis..."

    $docker = docker --version 2>$null
    if ($LASTEXITCODE -ne 0) {
        Write-Error "❌ Docker n'est pas installé"
        exit 1
    }

    $compose = docker-compose --version 2>$null
    if ($LASTEXITCODE -ne 0) {
        Write-Error "❌ Docker Compose n'est pas installé"
        exit 1
    }

    Write-Log "✅ Docker et Docker Compose détectés"
}

function Load-Env {
    Write-Log "⚙️ Chargement de la configuration..."

    if ($Env -eq "prod" -and (Test-Path ".env.production")) {
        Copy-Item ".env.production" ".env" -Force
        Write-Log "✅ Configuration production chargée"
    }
    elseif (-not (Test-Path ".env")) {
        Copy-Item ".env.example" ".env"
        Write-Log "⚠️ Configuration par défaut utilisée (à adapter!)"
    }
    else {
        Write-Log "✅ Fichier .env trouvé"
    }
}

function Stop-Services {
    Write-Log "🛑 Arrêt des services précédents..."
    docker-compose down 2>$null | Out-Null
    Write-Log "✅ Services arrêtés"
}

function Build-Image {
    if ($SkipBuild) {
        Write-Log "⏭️ Skipping build (--SkipBuild)"
        return
    }

    Write-Log "🔨 Construction de l'image Docker..."
    docker-compose build --no-cache
    if ($LASTEXITCODE -ne 0) {
        Write-Error "❌ Erreur lors de la construction"
        exit 1
    }
    Write-Log "✅ Image construite"
}

function Start-Services {
    Write-Log "▶️ Démarrage des services..."
    docker-compose up -d
    if ($LASTEXITCODE -ne 0) {
        Write-Error "❌ Erreur lors du démarrage"
        exit 1
    }
    Write-Log "✅ Services démarrés"

    Write-Log "⏳ Attente du démarrage de l'application..."
    Start-Sleep -Seconds 10
}

function Initialize-DB {
    Write-Log "🗄️ Initialisation de la base de données..."

    docker-compose exec -T app php artisan migrate --force 2>&1 | Tee-Object -FilePath $LogFile
    Write-Log "✅ Base de données initialisée"
}

function Show-Status {
    Write-Log ""
    Write-Log "📊 État des services:"
    docker-compose ps

    Write-Log ""
    Write-Log "📝 Derniers logs (app):"
    docker-compose logs --tail=20 app
}

# Main execution
try {
    Check-Requirements
    Load-Env
    Stop-Services
    Build-Image
    Start-Services
    Initialize-DB
    Show-Status

    Write-Log ""
    Write-Log "✨ Déploiement réussi!"
    Write-Log "🌍 Application disponible à: http://localhost"
    Write-Log "📖 Documentation: Voir DOCKER_DEPLOYMENT.md"
    Write-Log ""
    Write-Log "Commandes utiles:"
    Write-Log "  - Voir les logs:      docker-compose logs -f app"
    Write-Log "  - Terminal app:       docker-compose exec app sh"
    Write-Log "  - Artisan commands:   docker-compose exec app php artisan <cmd>"
    Write-Log "  - Arrêter services:   docker-compose down"
}
catch {
    Write-Error "❌ Erreur durant le déploiement: $_"
    exit 1
}
