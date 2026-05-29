@echo off
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php ..\composer.phar dump-autoload
pause