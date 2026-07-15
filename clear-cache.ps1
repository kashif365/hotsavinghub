Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Clearing ALL Laravel Caches..." -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Set-Location $PSScriptRoot

Write-Host ""
Write-Host "[1/6] Clearing application cache..." -ForegroundColor Yellow
php artisan cache:clear

Write-Host ""
Write-Host "[2/6] Clearing config cache..." -ForegroundColor Yellow
php artisan config:clear

Write-Host ""
Write-Host "[3/6] Clearing route cache..." -ForegroundColor Yellow
php artisan route:clear

Write-Host ""
Write-Host "[4/6] Clearing view cache..." -ForegroundColor Yellow
php artisan view:clear

Write-Host ""
Write-Host "[5/6] Clearing compiled views..." -ForegroundColor Yellow
$viewsPath = Join-Path $PSScriptRoot "storage\framework\views"
if (Test-Path $viewsPath) {
    Get-ChildItem -Path $viewsPath -Filter "*.php" | Remove-Item -Force
    Write-Host "Compiled views deleted." -ForegroundColor Green
} else {
    Write-Host "No compiled views found." -ForegroundColor Gray
}

Write-Host ""
Write-Host "[6/6] Clearing bootstrap cache..." -ForegroundColor Yellow
$bootstrapCachePath = Join-Path $PSScriptRoot "bootstrap\cache"
if (Test-Path $bootstrapCachePath) {
    Get-ChildItem -Path $bootstrapCachePath -Filter "*.php" | Remove-Item -Force
    Write-Host "Bootstrap cache deleted." -ForegroundColor Green
} else {
    Write-Host "No bootstrap cache found." -ForegroundColor Gray
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "All caches cleared successfully!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Note: In development mode, changes should" -ForegroundColor Yellow
Write-Host "now appear immediately. If not, try:" -ForegroundColor Yellow
Write-Host "  1. Hard refresh browser (Ctrl+F5)" -ForegroundColor White
Write-Host "  2. Clear browser cache" -ForegroundColor White
Write-Host "  3. Restart XAMPP/Apache" -ForegroundColor White
Write-Host ""
Read-Host "Press Enter to continue"

