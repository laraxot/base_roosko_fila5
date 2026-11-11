#!/usr/bin/env bash
# Rilascio di Roosko sul server. Uso (dalla radice del progetto, utente del web server o con sudo):
#
#   ./deploy.sh                # rilascio completo
#   SKIP_ASSETS=1 ./deploy.sh  # senza ricompilare gli asset (nessun JS/CSS cambiato)
#   SKIP_BACKUP=1 ./deploy.sh  # senza backup del database (sconsigliato)
#
# Passi: manutenzione -> backup DB -> dipendenze -> asset -> migrazioni -> cache -> riavvio code -> controlli.
# Se un passo fallisce l'app RESTA in manutenzione: si guarda l'errore, si corregge e si rilancia;
# `php artisan up` a mano solo se si e' sicuri che il database e il codice sono coerenti.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")" && pwd)"
APP="$ROOT/laravel"
ASSET_DIRS="${ASSET_DIRS:-Modules/Geo Modules/Roosko Modules/Activity Themes/TwentyOne}"
cd "$APP"

step() { printf '\n==> %s\n' "$*"; }

step "Manutenzione"
php artisan down --retry=60 --refresh=15 || true

if [ "${SKIP_BACKUP:-0}" != "1" ]; then
    step "Backup del database"
    "$ROOT/deploy/backup-db.sh"
fi

step "Dipendenze PHP"
export COMPOSER_ALLOW_SUPERUSER=1
if [ -f composer.lock ]; then
    composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
else
    # Senza composer.lock la risoluzione avviene qui (vedi README): il secondo giro applica il merge-plugin.
    composer install --no-dev --prefer-dist --no-interaction --no-scripts
    composer update --no-dev --prefer-dist --optimize-autoloader --no-interaction
fi

if [ "${SKIP_ASSETS:-0}" != "1" ]; then
    step "Asset (Vite): $ASSET_DIRS"
    for dir in $ASSET_DIRS; do
        [ -f "$dir/package.json" ] || continue
        ( cd "$dir" && { [ -f package-lock.json ] && npm ci --no-audit --no-fund || npm install --no-audit --no-fund; } && npm run build )
    done
fi

step "Migrazioni"
php artisan migrate --force --no-interaction

step "Cache di configurazione, route, viste ed eventi"
php artisan optimize:clear
php artisan optimize
php artisan filament:optimize

step "Riavvio dei worker delle code (ripartono con il codice nuovo)"
php artisan queue:restart

step "Permessi delle cartelle scrivibili"
chmod -R ug+rwX storage bootstrap/cache

step "Fuori manutenzione"
php artisan up

step "Controlli di produzione"
php artisan roosko:production-check

echo "DEPLOY_SUCCESS"
