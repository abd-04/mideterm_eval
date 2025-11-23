param(
    [string]$XamppPath = "C:\xampp",
    [switch]$Force = $false
)

$ErrorActionPreference = "Stop"

Write-Host "Real Estate Portal - Windows Installer" -ForegroundColor Cyan
Write-Host "======================================"

# 1. Configuration
if (-not (Test-Path $XamppPath)) {
    $inputPath = Read-Host "XAMPP not found at $XamppPath. Enter XAMPP installation path"
    if (-not [string]::IsNullOrWhiteSpace($inputPath)) {
        $XamppPath = $inputPath
    }
}

$mysqlPath = Join-Path $XamppPath "mysql\bin\mysql.exe"
$htdocsPath = Join-Path $XamppPath "htdocs"
$targetPath = Join-Path $htdocsPath "realestate_portal"

# 2. Check Prerequisites
if (-not (Test-Path $mysqlPath)) {
    Write-Error "MySQL executable not found at $mysqlPath. Please verify XAMPP installation path."
    exit 1
}

if (-not (Test-Path $htdocsPath)) {
    Write-Error "htdocs directory not found at $htdocsPath."
    exit 1
}

# 3. Database Setup
Write-Host "`n[1/2] Setting up Database..." -ForegroundColor Yellow
$dbName = "realestate_portal"
$sqlFile = "realestate_portal.sql"
$sqlFilePath = Resolve-Path $sqlFile

if (-not (Test-Path $sqlFilePath)) {
    Write-Error "SQL file '$sqlFile' not found in current directory."
    exit 1
}

try {
    # Create Database
    Write-Host "Creating database '$dbName'..."
    & $mysqlPath -u root -e "CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
    
    # Import SQL
    Write-Host "Importing schema and data..."
    # Use cmd /c for redirection as PowerShell doesn't support < directly for native commands in all versions
    $cmdArgs = "/c `"$mysqlPath`" -u root $dbName < `"$sqlFilePath`""
    Start-Process -FilePath "cmd.exe" -ArgumentList $cmdArgs -Wait -NoNewWindow -PassThru | Out-Null
    
    Write-Host "Database setup complete!" -ForegroundColor Green
} catch {
    Write-Error "Database setup failed. Ensure MySQL is running in XAMPP Control Panel."
    Write-Error $_
    exit 1
}

# 4. Project Files Setup
Write-Host "`n[2/2] Deploying Project Files..." -ForegroundColor Yellow

if (Test-Path $targetPath) {
    if (-not $Force) {
        $overwrite = Read-Host "Target directory '$targetPath' already exists. Overwrite? (Y/N)"
        if ($overwrite -ne 'Y' -and $overwrite -ne 'y') {
            Write-Host "Skipping deployment."
            exit 0
        }
    }
    Remove-Item -Path $targetPath -Recurse -Force
}

try {
    Write-Host "Copying files to '$targetPath'..."
    $exclude = @(".git", ".vscode", "node_modules", "install_windows.ps1", "INSTRUCTIONS_WINDOWS.md")
    
    New-Item -ItemType Directory -Force -Path $targetPath | Out-Null
    
    Get-ChildItem -Path . -Exclude $exclude | Copy-Item -Destination $targetPath -Recurse -Force
    
    Write-Host "Deployment complete!" -ForegroundColor Green
} catch {
    Write-Error "Failed to copy files."
    Write-Error $_
    exit 1
}

Write-Host "`n======================================"
Write-Host "Installation Successful!" -ForegroundColor Green
Write-Host "You can now access the portal at:"
Write-Host "http://localhost/realestate_portal/public" -ForegroundColor Cyan
Write-Host "======================================"
