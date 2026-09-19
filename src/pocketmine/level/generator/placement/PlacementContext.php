<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\Generator;
use pocketmine\level\Level;
use pocketmine\math\Vector3;

class PlacementContext {
	public function __construct(
		private ChunkManager $level,
		private Generator $generator
	){}

	public function getBlockState(Vector3 $pos) : Block {
		return $this->level->getBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
	}

	public function getMinY() : int {
		return Level::Y_MIN; //TODO:
	}

	public function getLevel() : ChunkManager {
		return $this->level;
	}

	public function getGenerator() : Generator {
		return $this->generator;
	}
}
