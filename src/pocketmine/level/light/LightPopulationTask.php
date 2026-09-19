<?php


declare(strict_types=1);

namespace pocketmine\level\light;

use pocketmine\block\BlockFactory;
use pocketmine\level\format\Chunk;
use pocketmine\level\format\io\FastChunkSerializer;
use pocketmine\level\Level;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class LightPopulationTask extends AsyncTask
{
	public $levelId;
	public $chunk;

	public function __construct(Level $level, Chunk $chunk)
	{
		$this->levelId = $level->getId();
		$this->chunk = FastChunkSerializer::serializeTerrain($chunk);
	}

	public function onRun() : void
	{
		if (!BlockFactory::isInit()) {
			BlockFactory::init();
		}

		$chunk = FastChunkSerializer::deserializeTerrain($this->chunk);

		$chunk->recalculateHeightMap();
		$chunk->populateSkyLight();
		$chunk->setLightPopulated();

		$this->chunk = FastChunkSerializer::serializeTerrain($chunk);
	}

	public function onCompletion(Server $server)
	{
		$level = $server->getLevel($this->levelId);
		if ($level !== null) {
			/** @var Chunk $chunk */
			$chunk = FastChunkSerializer::deserializeTerrain($this->chunk);
			$level->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
		}
	}
}
