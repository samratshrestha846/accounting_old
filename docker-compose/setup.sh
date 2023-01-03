#!/bin/sh

# docker-compose exec app composer install --no-interaction --prefer-dist --optimize-autoloader

docker-compose exec app php artisan storage:link || true
docker-compose exec app php artisan key:generate
docker-compose exec app chmod -R 775 storage/

# RUN php artisan route:cache
docker-compose exec app php artisan config:cache
