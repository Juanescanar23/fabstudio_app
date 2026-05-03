#!/usr/bin/env bash
set -euo pipefail

mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
chmod -R ug+rw bootstrap/cache storage

php artisan config:cache

exec php artisan automations:run
