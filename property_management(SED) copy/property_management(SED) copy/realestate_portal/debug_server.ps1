$ErrorActionPreference = "SilentlyContinue"

Write-Host "--- DEBUG INFO ---" -ForegroundColor Cyan

# 1. Find running Apache process
$apacheProcess = Get-Process -Name "httpd" | Select-Object -First 1 -ExpandProperty Path
if ($apacheProcess) {
    Write-Host "Running Apache: $apacheProcess" -ForegroundColor Green
    
    # Try to find httpd.conf
    $apacheRoot = Split-Path (Split-Path $apacheProcess)
    $confPath = Join-Path $apacheRoot "conf\httpd.conf"
    
    if (Test-Path $confPath) {
        Write-Host "Config File: $confPath" -ForegroundColor Green
        
        # Read DocumentRoot
        $content = Get-Content $confPath
        $docRootLine = $content | Where-Object { $_ -match "^DocumentRoot" }
        Write-Host "DocumentRoot Setting: $docRootLine" -ForegroundColor Yellow
        
        # Extract path
        if ($docRootLine -match '"([^"]+)"') {
            $docRoot = $matches[1]
            Write-Host "Actual DocumentRoot: $docRoot" -ForegroundColor Yellow
            
            # Check if our folder exists there
            $projectPath = Join-Path $docRoot "realestate_portal"
            if (Test-Path $projectPath) {
                Write-Host "Project Folder: FOUND at $projectPath" -ForegroundColor Green
            } else {
                Write-Host "Project Folder: NOT FOUND at $projectPath" -ForegroundColor Red
                
                # Check where we installed it
                Write-Host "Checking D:\xampp\htdocs\realestate_portal..."
                if (Test-Path "D:\xampp\htdocs\realestate_portal") {
                    Write-Host "Files are at D:\xampp\htdocs\realestate_portal (Wrong place?)" -ForegroundColor Magenta
                }
            }
        }
    } else {
        Write-Host "Could not find httpd.conf at $confPath" -ForegroundColor Red
    }
} else {
    Write-Host "Apache (httpd.exe) is NOT running." -ForegroundColor Red
}

Write-Host "--- END DEBUG ---" -ForegroundColor Cyan
