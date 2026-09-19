<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\blocksupport;

use pocketmine\block\Dirt;
use pocketmine\block\Farmland;
use pocketmine\block\Grass;
use pocketmine\block\Mud;
use pocketmine\block\Mycelium;
use pocketmine\block\Podzol;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;

class PlantSupport implements BlockSupport {

	public function isValidPosition(ChunkManager $level, Vector3 $origin) : bool {
		$down = $level->getBlockAt($origin->x, $origin->y - 1, $origin->z);
		return
			$down instanceof Grass ||
			$down instanceof Dirt ||
			$down instanceof Mycelium ||
			$down instanceof Podzol ||
			$down instanceof Farmland ||
			$down instanceof Mud;
	}
}
