<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use pocketmine\block\BlockIds;
use pocketmine\level\format\Chunk;
use pocketmine\level\format\io\leveldb\ChunkDataKey;
use pocketmine\level\format\io\leveldb\ChunkVersion;
use pocketmine\level\format\io\leveldb\LevelDB;
use pocketmine\level\generator\GeneratorManager;
use pocketmine\level\LevelCreationOptions;

GeneratorManager::registerDefaultGenerators();
$path = dirname(__DIR__) . '/build/leveldb-version-' . bin2hex(random_bytes(6));
LevelDB::generate($path, 'version-check', LevelCreationOptions::create());
$provider = new LevelDB($path);
$index = LevelDB::chunkIndex(0, 0);
$provider->getDatabase()->put($index . ChunkDataKey::NEW_VERSION, chr(ChunkVersion::v1_21_40));
$chunk = new Chunk(0, 0);
$chunk->setBlockId(1, 64, 1, BlockIds::STONE);
$provider->saveChunk($chunk);
$provider->close();

$provider = new LevelDB($path);
$database = $provider->getDatabase();
if ($database->get($index . ChunkDataKey::NEW_VERSION) !== false) {
	throw new RuntimeException('Stale new version key survived saving');
}
if ($database->get($index . ChunkDataKey::OLD_VERSION) !== chr(ChunkVersion::v1_2_0)) {
	throw new RuntimeException('Saved chunk version was not written');
}
$loaded = $provider->loadChunk(0, 0);
if ($loaded === null || $loaded->getBlockId(1, 64, 1) !== BlockIds::STONE) {
	throw new RuntimeException('Saved block could not be loaded');
}
$provider->close();
echo "PASS: LevelDB version keys after save\n";
