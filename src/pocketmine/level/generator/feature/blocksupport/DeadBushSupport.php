<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\blocksupport;

use pocketmine\block\BlockIds;
use pocketmine\block\Mud;
use pocketmine\block\Sand;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;

class DeadBushSupport implements BlockSupport {

	public function isValidPosition(ChunkManager $level, Vector3 $origin) : bool {
		$down = $level->getBlockAt($origin->x, $origin->y - 1, $origin->z);
		return
			$down instanceof Sand ||
			$down instanceof Mud ||
			match ($down->getId()) {
				//can't use DIRT tag here because it includes farmland
				BlockIds::PODZOL,
				BlockIds::MYCELIUM,
				BlockIds::DIRT,
				BlockIds::GRASS,
				BlockIds::HARDENED_CLAY,
				BlockIds::MOSS_BLOCK,
				BlockIds::STAINED_CLAY => true,
				default => false,
			};
	}
}
