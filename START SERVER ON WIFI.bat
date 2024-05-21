@echo off

@echo MAKE SURE YOU DISABLE YOUR FIREWALL

setlocal enabledelayedexpansion

REM Get the IP address of the computer
set ip_address=
for /f "tokens=2 delims=:" %%i in ('ipconfig ^| findstr /i "IPv4"') do set ip_address=%%i
set ip_address=!ip_address: =!

REM Run Laravel with the IP address
php artisan serve --host=!ip_address!:2024
