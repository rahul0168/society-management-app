#!/bin/bash
# Non-interactive startup script for cloud hosting environments (PHPix / Docker / Railway / Render)

echo "Running migrations and seeders..."
php artisan migrate --force --seed --no-interaction

echo "Clearing application caches..."
php artisan config:clear
php artisan cache:clear

echo "Done!"
