#!/usr/bin/env bash

set -euo pipefail

if [ ! -f .env ]; then
    cp .env.sail.example .env
fi

docker compose build
docker compose run --rm laravel.test composer install --no-interaction
docker compose run --rm laravel.test php artisan key:generate --force
docker compose up -d
docker compose exec laravel.test php artisan migrate --seed --force
docker compose exec -e CI=true laravel.test pnpm install --frozen-lockfile
docker compose exec laravel.test pnpm run build

echo "Sail setup completed: http://localhost:8080"
