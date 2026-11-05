#!/usr/bin/env bash

# abilito l'uscita immediata in caso di errore
set -e

echo "Inizio deploy..."

# mi sposto nella working dir
#echo "Cambio directory..."
#cd /var/www/<nome progetto>le

# evito l'errore "fatal: detected dubious ownership in repository"
#echo "Configurazione safe.directory..."
#git config --global --add safe.directory /var/www/<nome progetto>le

# eseguo pull
#echo "Esecuzione git pull..."
#if [ -z "$GITLAB_TOKEN" ]; then
#  git pull
#else
#  # serve passare esplicitamente la variabile GITLAB_TOKEN a altrimenti la si perde
#  GITLAB_TOKEN="$GITLAB_TOKEN" git -c credential.helper= -c credential.helper='!f() { echo username=gitlab-ci-token; #echo password=$GITLAB_TOKEN; }; f' pull
#fi

# aggiornamento applicazione
cd laravel
echo "Aggiorno composer..."
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php -d memory_limit=-1 composer.phar selfupdate
rm -rf ./app/View/Components/vendor/
rm -rf ./resources/views/vendor/
echo "Aggiorno librerie..."
COMPOSER_ALLOW_SUPERUSER=1 php -d memory_limit=-1 composer.phar update -W --no-interaction 1>/dev/null
echo "Pubblico variabili e configurazioni..."
php artisan vendor:publish --all --quiet
rm -rf database/migrations/*
rm -rf ./app/View/Components/vendor/
echo "Eseguo migrazioni..."
php artisan migrate --force --no-interaction --quiet
echo "Aggiorno Filament..."
php artisan filament:upgrade --quiet
echo "Ottimizzo Filament..."
php artisan filament:optimize --quiet
echo "Ottimizzo applicazione..."
php artisan optimize --quiet
echo "Pulizia route..."
php artisan route:clear --quiet
cd ..

# riaggiorno i permessi
echo "Aggiornamento permessi..."
sudo chown -R www-data:www-data .
sudo chmod -R g+w .

echo "Deploy completato con successo!"

echo "DEPLOY_SUCCESS"
