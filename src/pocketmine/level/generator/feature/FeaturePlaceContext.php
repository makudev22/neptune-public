<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class FeaturePlaceContext {
	public function __construct(
		private ChunkManager $level,
		private Random $random,
		private Vector3 $origin
	){}

	public function level() : ChunkManager {
		return $this->level;
	}

	public function random() : Random {
		return $this->random;
	}

	public function origin() : Vector3 {
		return $this->origin;
	}
}
