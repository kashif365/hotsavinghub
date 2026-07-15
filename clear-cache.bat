@echo off
echo ========================================
echo Clearing ALL Laravel Caches...
echo ========================================
cd /d "%~dp0"

echo.
echo [1/6] Clearing application cache...
php artisan cache:clear

echo.
echo [2/6] Clearing config cache...
php artisan config:clear

echo.
echo [3/6] Clearing route cache...
php artisan route:clear

echo.
echo [4/6] Clearing view cache...
php artisan view:clear

echo.
echo [5/6] Clearing compiled views...
if exist "storage\framework\views\*" (
    del /Q "storage\framework\views\*"
    echo Compiled views deleted.
) else (
    echo No compiled views found.
)

echo.
echo [6/6] Clearing bootstrap cache...
if exist "bootstrap\cache\*.php" (
    del /Q "bootstrap\cache\*.php"
    echo Bootstrap cache deleted.
) else (
    echo No bootstrap cache found.
)

echo.
echo ========================================
echo All caches cleared successfully!
echo ========================================
echo.
echo Note: In development mode, changes should
echo now appear immediately. If not, try:
echo   1. Hard refresh browser (Ctrl+F5)
echo   2. Clear browser cache
echo   3. Restart XAMPP/Apache
echo.
pause

