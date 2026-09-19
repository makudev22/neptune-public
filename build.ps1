param(
    [string]$Php = $env:NEPTUNE_PHP
)

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $root

if ([string]::IsNullOrWhiteSpace($Php)) {
    $candidate = Join-Path $root 'bin\php\php.exe'
    if (Test-Path -LiteralPath $candidate) {
        $Php = $candidate
    }
}

if ([string]::IsNullOrWhiteSpace($Php) -or -not (Test-Path -LiteralPath $Php)) {
    throw 'Set NEPTUNE_PHP to the PocketMine-MP PHP 8.3 executable or place it in bin\php\php.exe.'
}

if (-not (Test-Path -LiteralPath (Join-Path $root 'vendor\autoload.php'))) {
    $composer = Get-Command composer -ErrorAction SilentlyContinue
    if ($null -eq $composer) {
        throw 'vendor is missing. Install Composer and run composer install --no-dev --prefer-dist --optimize-autoloader.'
    }
    & $composer.Source install --no-dev --prefer-dist --optimize-autoloader
}

& $Php -d phar.readonly=0 .\tools\build-phar.php
& $Php .\tools\verify-protocols.php
