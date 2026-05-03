#!/usr/bin/env bash
set -euo pipefail

mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
chmod -R ug+rw bootstrap/cache storage

php artisan config:cache

exec php artisan queue:work database \
  --queue=automations,notifications,default \
  --sleep=3 \
  --tries=3 \
  --timeout=90 \
  --max-time=3600
