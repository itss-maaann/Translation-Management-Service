#!/usr/bin/env bash

set -e

until mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" &>/dev/null; do
  echo "Waiting for MySQL…"
  sleep 2
done

if [ ! -d "./vendor" ]; then
  composer install --optimize-autoloader --no-interaction --prefer-dist
  php artisan key:generate
fi

php artisan migrate --force
php artisan db:seed --force

php artisan l5-swagger:generate

# start PHP-FPM
exec "$@"
