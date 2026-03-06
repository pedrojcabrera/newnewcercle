# Script para eliminar $_SERVER['REQUEST_SCHEME'] de todas las llamadas a base_url()

$projectPath = "c:\laragon\www\newCercle"
$backupPath = "c:\laragon\www\newCercle\backup_before_fix"

Write-Host "=== Corrigiendo base_url() ===" -ForegroundColor Green
Write-Host ""

# Crear backup
Write-Host "Creando backup..." -ForegroundColor Yellow
if (!(Test-Path $backupPath)) {
    New-Item -ItemType Directory -Path $backupPath -Force | Out-Null
}
Copy-Item -Path "$projectPath\app" -Destination "$backupPath\app" -Recurse -Force
Write-Host "Backup creado en: $backupPath" -ForegroundColor Green
Write-Host ""

# Buscar archivos PHP
$phpFiles = Get-ChildItem -Path "$projectPath\app" -Filter *.php -Recurse

Write-Host "Procesando $($phpFiles.Count) archivos..." -ForegroundColor Cyan
Write-Host ""

$count = 0

foreach ($file in $phpFiles) {
    $content = Get-Content $file.FullName -Raw
    $original = $content

    # Reemplazar las tres variantes
    $content = $content.Replace(", `$_SERVER['REQUEST_SCHEME'])", ")")
    $content = $content.Replace(', $_SERVER["REQUEST_SCHEME"])', ")")
    $content = $content.Replace(",`$_SERVER['REQUEST_SCHEME']", "")

    if ($content -ne $original) {
        Set-Content -Path $file.FullName -Value $content -NoNewline
        $count++
        Write-Host "✓ $($file.Name)" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "=== Completado ===" -ForegroundColor Green
Write-Host "Archivos modificados: $count" -ForegroundColor Cyan
Write-Host "Backup: $backupPath" -ForegroundColor Yellow
