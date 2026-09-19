<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\blockplacer;

use pocketmine\block\Block;
use pocketmine\block\DoublePlant;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class DoublePlantBlockPlacer implements BlockPlacer{

	public function place(ChunkManager $level, Vector3 $pos, Block $state, Random $random) : void {
		$level->setBlockAt($pos->x, $pos->y, $pos->z, $state);
		$level->setBlockAt($pos->x, $pos->y + 1, $pos->z, (clone $state)->setDamage($state->getDamage() | DoublePlant::BITFLAG_TOP));
	}
}
