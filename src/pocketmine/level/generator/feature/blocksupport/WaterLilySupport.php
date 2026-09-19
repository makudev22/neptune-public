<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\blocksupport;

use pocketmine\block\Water;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;

class WaterLilySupport implements BlockSupport {

	public function isValidPosition(ChunkManager $level, Vector3 $origin) : bool {
		$down = $level->getBlockAt($origin->x, $origin->y - 1, $origin->z);
		return $down instanceof Water;
	}
}
