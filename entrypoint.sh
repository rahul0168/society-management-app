#!/bin/bash
# Non-interactive startup script for cloud hosting environments (PHPix / Docker / Railway / Render)

echo "Running non-interactive database migrations..."
php artisan migrate --force --no-interaction

echo "Seeding initial database records..."
php artisan db:seed --force --no-interaction

echo "Clearing application caches..."
php artisan config:clear
php artisan cache:clear

echo "Starting server..."
