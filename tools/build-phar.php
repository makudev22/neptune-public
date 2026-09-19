<?php

declare(strict_types=1);

if (ini_get('phar.readonly') === '1') {
	fwrite(STDERR, "Run with phar.readonly=0\n");
	exit(1);
}

$root = dirname(__DIR__);
$outputDirectory = $root . DIRECTORY_SEPARATOR . 'build';
$output = $outputDirectory . DIRECTORY_SEPARATOR . 'Neptune.phar';

if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, true) && !is_dir($outputDirectory)) {
	throw new RuntimeException('Unable to create build directory');
}
if (is_file($output) && !unlink($output)) {
	throw new RuntimeException('Unable to replace existing PHAR');
}

$phar = new Phar($output, 0, 'Neptune.phar');
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

echo $output . PHP_EOL;
