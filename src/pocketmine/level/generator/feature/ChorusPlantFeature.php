<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\block\BlockIds;
use pocketmine\block\ChorusFlower;

class ChorusPlantFeature extends Feature {

	public function place(FeaturePlaceContext $context) : bool{
		$level = $context->level();
		$origin = $context->origin();
		$random = $context->random();

		$block = $level->getBlockAt($origin->getX(), $origin->getY(), $origin->getZ());
		$down = $level->getBlockAt($origin->getX(), $origin->getY() - 1, $origin->getZ());
		if ($block->getId() === BlockIds::AIR && $down->getId() === BlockIds::END_STONE) {
			ChorusFlower::generatePlant($level, $origin, $random, 8);
			return true;
		}

		return false;
	}
}
