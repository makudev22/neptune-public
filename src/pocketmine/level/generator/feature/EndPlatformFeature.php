<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;

class EndPlatformFeature extends Feature {

	public function place(FeaturePlaceContext $context) : bool{
		$level = $context->level();
		$origin = $context->origin();

		$obsidian = BlockFactory::get(BlockIds::OBSIDIAN);
		$air = BlockFactory::get(BlockIds::AIR);
		for ($dz = -2; $dz <= 2; $dz++) {
			for ($dx = -2; $dx <= 2; $dx++) {
				for ($dy = -1; $dy < 3; $dy++) {
					$blockPos = $origin->add($dx, $dy, $dz);
					$block = $dy == -1 ? $obsidian : $air;
					if (!$level->getBlockAt($blockPos->getX(), $blockPos->getY(), $blockPos->getZ())->isSameType($block)) {
						//TODO: destroyBlock (dropResources)

						$level->setBlockAt($blockPos->getX(), $blockPos->getY(), $blockPos->getZ(), $block);
					}
				}
			}
		}

		return true;
	}
}
