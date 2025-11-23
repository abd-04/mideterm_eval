Write-Host "Stopping background XAMPP processes..." -ForegroundColor Yellow

# Stop MySQL
$mysql = Get-Process -Name "mysqld" -ErrorAction SilentlyContinue
if ($mysql) {
    Stop-Process -Name "mysqld" -Force
    Write-Host "Stopped mysqld.exe" -ForegroundColor Green
} else {
    Write-Host "MySQL was not running."
}

# Stop Apache
$apache = Get-Process -Name "httpd" -ErrorAction SilentlyContinue
if ($apache) {
    Stop-Process -Name "httpd" -Force
    Write-Host "Stopped httpd.exe" -ForegroundColor Green
} else {
    Write-Host "Apache was not running."
}

Write-Host "`nDone! You can now open XAMPP Control Panel and click 'Start' for Apache and MySQL." -ForegroundColor Cyan
Write-Host "Press any key to exit..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
