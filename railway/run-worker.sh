#!/usr/bin/env bash
set -euo pipefail

php artisan config:cache

exec php artisan queue:work database \
  --sleep=3 \
  --tries=3 \
  --timeout=90 \
  --max-time=3600
