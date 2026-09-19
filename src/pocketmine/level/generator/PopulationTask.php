<?php


declare(strict_types=1);

namespace pocketmine\level\generator;

use pocketmine\level\format\Chunk;
use pocketmine\level\format\io\FastChunkSerializer;
use pocketmine\level\Level;
use pocketmine\level\SimpleChunkManager;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class PopulationTask extends AsyncTask
{
	public $state;
	public $levelId;
	public $chunk;

	public $chunk0;
	public $chunk1;
	public $chunk2;
	public $chunk3;
	//center chunk
	public $chunk5;
	public $chunk6;
	public $chunk7;
	public $chunk8;

	public function __construct(Level $level, Chunk $chunk)
	{
		$this->state = true;
		$this->levelId = $level->getId();
		$this->chunk = FastChunkSerializer::serializeTerrain($chunk);

		foreach ($level->getAdjacentChunks($chunk->getX(), $chunk->getZ()) as $i => $c) {
			$this->{"chunk$i"} = $c !== null ? FastChunkSerializer::serializeTerrain($c) : null;
		}
	}

	public function onRun() : void{
		$manager = $this->getFromThreadStore("generation.level{$this->levelId}.manager");
		$generator = $this->getFromThreadStore("generation.level{$this->levelId}.generator");
		if(!($manager instanceof SimpleChunkManager) || !($generator instanceof Generator)){
			$this->state = false;
			return;
		}

		/** @var Chunk[] $chunks */
		$chunks = [];

		$chunk = FastChunkSerializer::deserializeTerrain($this->chunk);

		for ($i = 0; $i < 9; ++$i) {
			if ($i === 4) {
				continue;
			}
			$xx = -1 + $i % 3;
			$zz = -1 + (int) ($i / 3);
			$ck = $this->{"chunk$i"};
			if ($ck === null) {
				$chunks[$i] = new Chunk($chunk->getX() + $xx, $chunk->getZ() + $zz);
			} else {
				$chunks[$i] = FastChunkSerializer::deserializeTerrain($ck);
			}
		}

		$manager->setChunk($chunk->getX(), $chunk->getZ(), $chunk);
		if(!$chunk->isGenerated()){
			$generator->generateChunk($chunk->getX(), $chunk->getZ());
			$chunk = $manager->getChunk($chunk->getX(), $chunk->getZ());
			$chunk->setGenerated();
		}

		foreach($chunks as $i => $c){
			$manager->setChunk($c->getX(), $c->getZ(), $c);
			if(!$c->isGenerated()){
				$generator->generateChunk($c->getX(), $c->getZ());
				$chunks[$i] = $manager->getChunk($c->getX(), $c->getZ());
				$chunks[$i]->setGenerated();
			}
		}

		$generator->populateChunk($chunk->getX(), $chunk->getZ());
		foreach ($manager->getEntities() as $entity) {
			$chunkX = $entity->getFloorX() >> Chunk::COORD_BIT_SIZE;
			$chunkZ = $entity->getFloorZ() >> Chunk::COORD_BIT_SIZE;

			$entityChunk = $manager->getChunk($chunkX, $chunkZ);
			if ($entityChunk !== null) {
				$entity->saveNBT();
				$entityChunk->addNBTEntity($entity->namedtag);
			}
		}

		foreach ($manager->getTiles() as $tile) {
			$chunkX = $tile->getFloorX() >> Chunk::COORD_BIT_SIZE;
			$chunkZ = $tile->getFloorZ() >> Chunk::COORD_BIT_SIZE;

			$tileChunk = $manager->getChunk($chunkX, $chunkZ);
			if ($tileChunk !== null) {
				$tileChunk->addNBTTile($tile->saveNBT());
			}
		}

		$chunk = $manager->getChunk($chunk->getX(), $chunk->getZ());
		$chunk->setPopulated();

		$chunk->recalculateHeightMap();
		$chunk->populateSkyLight();
		$chunk->setLightPopulated();

		$this->chunk = FastChunkSerializer::serializeTerrain($chunk, true);

		$manager->setChunk($chunk->getX(), $chunk->getZ(), null);

		foreach($chunks as $i => $c){
			if($c !== null){
				$c = $chunks[$i] = $manager->getChunk($c->getX(), $c->getZ());
				if(!$c->hasChanged()){
					$chunks[$i] = null;
				}
			}else{
				//This way non-changed chunks are not set
				$chunks[$i] = null;
			}
		}

		$manager->cleanChunks();

		for($i = 0; $i < 9; ++$i){
			if($i === 4){
				continue;
			}

			$this->{"chunk$i"} = $chunks[$i] !== null ? FastChunkSerializer::serializeTerrain($chunks[$i], true) : null;
		}
	}

	public function onCompletion(Server $server) : void{
		$level = $server->getLevel($this->levelId);
		if ($level !== null) {
			if (!$this->state) {
				$level->registerGeneratorToWorker($this->getWorker()->getAsyncWorkerId());
			}

			$chunk = FastChunkSerializer::deserializeTerrain($this->chunk, true);

			for ($i = 0; $i < 9; ++$i) {
				if ($i === 4) {
					continue;
				}
				$c = $this->{"chunk$i"};
				if ($c !== null) {
					$c = FastChunkSerializer::deserializeTerrain($c, true);
					$level->generateChunkCallback($c->getX(), $c->getZ(), $this->state ? $c : null);
				}
			}

			$level->generateChunkCallback($chunk->getX(), $chunk->getZ(), $this->state ? $chunk : null);
		}
	}
}
