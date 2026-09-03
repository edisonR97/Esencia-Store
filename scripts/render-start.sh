#!/bin/sh
set -e

php artisan storage:link --force
php artisan migrate --force
php artisan db:seed --force
php artisan optimize

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
