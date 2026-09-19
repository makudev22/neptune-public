<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use function abs;

class EndGatewayFeature extends Feature {

	public function place(FeaturePlaceContext $context) : bool{
		$level = $context->level();
		$origin = $context->origin();

		$minX = $origin->getX() - 1;
		$maxX = $origin->getX() + 1;
		$minY = $origin->getY() - 2;
		$maxY = $origin->getY() + 2;
		$minZ = $origin->getZ() - 1;
		$maxZ = $origin->getZ() + 1;

		for ($x = $minX; $x <= $maxX; $x++) {
			for ($y = $minY; $y <= $maxY; $y++) {
				for ($z = $minZ; $z <= $maxZ; $z++) {
					$sameX = $x === $origin->getX();
					$sameY = $y === $origin->getY();
					$sameZ = $z === $origin->getZ();
					$end = abs($y - $origin->getY()) === 2;

					if ($sameX && $sameY && $sameZ) {
						$level->setBlockAt($x, $y, $z, BlockFactory::get(BlockIds::END_GATEWAY));
						//TODO: create Tile
					} elseif ($sameY) {
						$level->setBlockAt($x, $y, $z, BlockFactory::get(BlockIds::AIR));
					} elseif ($end && $sameX && $sameZ) {
						$level->setBlockAt($x, $y, $z, BlockFactory::get(BlockIds::BEDROCK));
					} elseif (($sameX || $sameZ) && !$end) {
						$level->setBlockAt($x, $y, $z, BlockFactory::get(BlockIds::BEDROCK));
					} else {
						$level->setBlockAt($x, $y, $z, BlockFactory::get(BlockIds::AIR));
					}
				}
			}
		}

		return true;
	}
}
