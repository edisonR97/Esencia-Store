#!/bin/sh
set -e

# Render's generated value does not use Laravel's required key format.
# Create a valid AES-256 key at container startup without storing it in Git.
APP_KEY="$(php artisan key:generate --show --no-ansi)"
export APP_KEY

php artisan storage:link --force
php artisan migrate --force
php artisan db:seed --force
php artisan optimize

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
