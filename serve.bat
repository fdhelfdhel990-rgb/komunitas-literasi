@echo off
title Laravel Development Server - Komunitas Literasi
echo Menjalankan Laravel Artisan Serve di port 8001...

if exist "D:\xampp\php\php.exe" (
    "D:\xampp\php\php.exe" -d opcache.enable=0 artisan serve --port=8001
) else if exist "C:\xampp\php\php.exe" (
    "C:\xampp\php\php.exe" -d opcache.enable=0 artisan serve --port=8001
) else if exist "D:\Apps\php84\php.exe" (
    "D:\Apps\php84\php.exe" -d opcache.enable=0 artisan serve --port=8001
) else (
    php -d opcache.enable=0 artisan serve --port=8001
)
pause
