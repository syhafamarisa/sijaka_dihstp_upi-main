@echo off
title Laravel LAN Server
echo ==========================================================
echo       MENJALANKAN LARAVEL SERVER UNTUK JARINGAN LOKAL
echo ==========================================================
echo.

:: Mendeteksi IP lokal secara dinamis menggunakan PowerShell
for /f "usebackq tokens=*" %%a in (`powershell -NoProfile -Command "(Get-NetIPAddress -AddressFamily IPv4 | Where-Object { $_.IPAddress -ne '127.0.0.1' -and $_.IPAddress -notlike '169.254.*' } | Select-Object -First 1).IPAddress"`) do (
    set "IP=%%a"
)

if "%IP%"=="" (
    set "IP=127.0.0.1"
    echo [Peringatan] IP Jaringan tidak terdeteksi, pastikan Anda terhubung ke Wi-Fi.
)

echo IP Komputer Server saat ini: %IP%
echo.
echo Silakan akses website dari HP atau komputer lain dengan link:
echo http://%IP%:8000
echo.
echo Pastikan HP / komputer lain terhubung ke WI-FI yang SAMA.
echo Tekan Ctrl + C untuk menghentikan server.
echo.
echo ==========================================================
php artisan serve --host=0.0.0.0 --port=8000
pause
