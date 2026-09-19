param(
    [string]$Php = $env:NEPTUNE_PHP,
    [string]$DataPath = '.\server-data'
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

$phar = Join-Path $root 'build\Neptune.phar'
if (-not (Test-Path -LiteralPath $phar)) {
    throw 'build\Neptune.phar is missing. Run .\build.ps1 first.'
}

& $Php $phar "--data=$DataPath" "--plugins=$DataPath\plugins" --settings.enable-dev-builds=true
