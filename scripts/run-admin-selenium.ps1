$ErrorActionPreference = "Stop"

$root = Split-Path -Parent $PSScriptRoot
Set-Location $root

$chromeDriverPath = Join-Path $root "vendor\laravel\dusk\bin\chromedriver-win.exe"

if (-not (Test-Path $chromeDriverPath)) {
    throw "ChromeDriver not found at: $chromeDriverPath"
}

function Stop-ProcessSafe([int]$Id) {
    if ($Id -gt 0) {
        try { Stop-Process -Id $Id -Force -ErrorAction Stop } catch {}
    }
}

$serverProc = $null
$driverProc = $null

try {
    $driverProc = Start-Process -FilePath $chromeDriverPath -ArgumentList @("--port=9515") -PassThru -WindowStyle Hidden
    Start-Sleep -Seconds 2

    $serverProc = Start-Process -FilePath "php" -ArgumentList @("-S", "127.0.0.1:8000", "-t", "public") -PassThru -WindowStyle Hidden
    Start-Sleep -Seconds 2

    php artisan migrate:fresh --seed --force
    php artisan dusk --filter=AdminUiAutomationTest
}
finally {
    if ($serverProc) { Stop-ProcessSafe $serverProc.Id }
    if ($driverProc) { Stop-ProcessSafe $driverProc.Id }
}
