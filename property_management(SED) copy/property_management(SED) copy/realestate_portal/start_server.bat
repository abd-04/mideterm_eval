@echo off
title Real Estate Portal Server
color 0A

echo ====================================================
echo      REAL ESTATE PORTAL - EVALUATION SERVER
echo ====================================================
echo.
echo [1] Checking configuration...

if not exist "D:\xampp\php\php.exe" (
    echo ERROR: PHP not found at D:\xampp\php\php.exe
    echo Please check your XAMPP installation.
    pause
    exit
)

echo [2] Starting Server...
echo.
echo Server is running at: http://localhost:8081
echo.
echo DO NOT CLOSE THIS WINDOW while using the site.
echo.
echo Opening browser...
start http://localhost:8081
echo.

"D:\xampp\php\php.exe" -S localhost:8081 -t public
pause
