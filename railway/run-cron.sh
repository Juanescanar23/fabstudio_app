#!/usr/bin/env bash
set -euo pipefail

php artisan config:cache

exec php artisan automations:run
