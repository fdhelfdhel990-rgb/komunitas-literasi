@echo off
if exist "D:\xampp\php\php.exe" (
    "D:\xampp\php\php.exe" -d opcache.enable=0 %*
) else if exist "C:\xampp\php\php.exe" (
    "C:\xampp\php\php.exe" -d opcache.enable=0 %*
) else if exist "D:\Apps\php84\php.exe" (
    "D:\Apps\php84\php.exe" -d opcache.enable=0 %*
) else (
    php -d opcache.enable=0 %*
)
