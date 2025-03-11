@echo off
cd /d "%~dp0"
C:\laragon\bin\php\php-8.2.12-Win32-vs16-x64\php.exe artisan storage:link
pause