<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use function ceil;
use function floor;

class EndIslandFeature extends Feature {

	public function place(FeaturePlaceContext $context) : bool{
		$level = $context->level();
		$origin = $context->origin();
		$random = $context->random();

		$size = $random->nextBoundedInt(3) + 4.0;

		$endStone = BlockFactory::get(BlockIds::END_STONE);
		for ($y = 0; $size > 0.5; $y--) {
			for ($x = floor(-$size); $x <= ceil($size); $x++) {
				for ($z = floor(-$size); $z <= ceil($size); $z++) {
					if ($x * $x + $z * $z <= ($size + 1.0) * ($size + 1.0)) {
						$pos = $origin->add($x, $y, $z);
						$level->setBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), clone $endStone);
					}
				}
			}

			$size -= $random->nextBoundedInt(2) + 0.5;
		}

		return true;
	}
}
