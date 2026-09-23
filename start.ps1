param(
    [string]$Php = $env:NEPTUNE_PHP,
    [string]$DataPath = $env:NEPTUNE_DATA
)

$ErrorActionPreference = 'Stop'
$callerDirectory = (Get-Location).Path
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
if (-not [string]::IsNullOrWhiteSpace($Php) -and -not [System.IO.Path]::IsPathRooted($Php)) {
    $Php = [System.IO.Path]::GetFullPath((Join-Path $callerDirectory $Php))
}
if ([string]::IsNullOrWhiteSpace($DataPath)) {
    $DataPath = Join-Path $root 'server-data'
} elseif (-not [System.IO.Path]::IsPathRooted($DataPath)) {
    $DataPath = [System.IO.Path]::GetFullPath((Join-Path $callerDirectory $DataPath))
}
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

& $Php $phar "--data=$DataPath" "--plugins=$(Join-Path $DataPath 'plugins')" --settings.enable-dev-builds=true
exit $LASTEXITCODE
