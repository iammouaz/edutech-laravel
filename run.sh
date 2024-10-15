#!/bin/sh
cron
cd /app
composer install
composer dump-autoload
php artisan migrate
php artisan db:seed
php artisan optimize:clear
php artisan cache:clear
php artisan storage:link

php artisan serve --host=0.0.0.0 --port=8000

# tail -F README.md
