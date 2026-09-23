<?php

declare(strict_types=1);

if (ini_get('phar.readonly') === '1') {
	fwrite(STDERR, "Run with phar.readonly=0\n");
	exit(1);
}

$root = dirname(__DIR__);
$outputDirectory = $root . DIRECTORY_SEPARATOR . 'build';
$outputName = $argv[1] ?? 'Neptune.phar';
if (basename($outputName) !== $outputName || !str_ends_with($outputName, '.phar')) {
	throw new InvalidArgumentException('Output must be a PHAR filename');
}
$output = $outputDirectory . DIRECTORY_SEPARATOR . $outputName;
$temporary = $outputDirectory . DIRECTORY_SEPARATOR . 'Neptune-' . bin2hex(random_bytes(8)) . '.phar';

if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, true) && !is_dir($outputDirectory)) {
	throw new RuntimeException('Unable to create build directory');
}
$phar = new Phar($temporary, 0, 'Neptune.phar');
$phar->startBuffering();

foreach (['src', 'vendor', 'LICENSE', 'README.md'] as $path) {
	if (!file_exists($root . DIRECTORY_SEPARATOR . $path)) {
		throw new RuntimeException("Missing build input: $path");
	}
}

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
$files = new CallbackFilterIterator($iterator, static function (SplFileInfo $file) use ($root) : bool {
	if (!$file->isFile()) {
		return false;
	}
	$path = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
	return $path === 'LICENSE' || $path === 'README.md' || str_starts_with($path, 'src/') || str_starts_with($path, 'vendor/');
});
$phar->buildFromIterator($files, $root);

$phar->setStub("<?php Phar::mapPhar('Neptune.phar'); require 'phar://Neptune.phar/src/pocketmine/PocketMine.php'; __HALT_COMPILER();");
$phar->setSignatureAlgorithm(Phar::SHA256);
$phar->stopBuffering();
unset($phar);

if (!rename($temporary, $output)) {
	throw new RuntimeException("Unable to replace $output; completed build remains at $temporary");
}

echo $output . PHP_EOL;
