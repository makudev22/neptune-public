<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\feature\configurations\HugeMushroomFeatureConfiguration;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class HugeBrownMushroomFeature extends AbstractHugeMushroomFeature {

	protected function makeCap(
		ChunkManager                     $level,
		Random                           $random,
		Vector3                          $origin,
		int                              $treeHeight,
		HugeMushroomFeatureConfiguration $config
	) : void {
		$radius = $config->foliageRadius;

		for ($dx = -$radius; $dx <= $radius; $dx++) {
			for ($dz = -$radius; $dz <= $radius; $dz++) {
				$minX = $dx == -$radius;
				$maxX = $dx == $radius;
				$minZ = $dz == -$radius;
				$maxZ = $dz == $radius;
				$xEdge = $minX || $maxX;
				$zEdge = $minZ || $maxZ;
				if (!$xEdge || !$zEdge) {
					$blockPos = $origin->add($dx, $treeHeight, $dz);
					$state = $config->capProvider->getState($random, $origin);
					$west = $minX || $zEdge && $dx == 1 - $radius;
					$east = $maxX || $zEdge && $dx == $radius - 1;
					$north = $minZ || $xEdge && $dz == 1 - $radius;
					$south = $maxZ || $xEdge && $dz == $radius - 1;

					if ($west && !$east && !$north && !$south) {
						$state->setDamage(4); // WEST
					} elseif (!$west && $east && !$north && !$south) {
						$state->setDamage(6); // EAST
					} elseif (!$west && !$east && $north && !$south) {
						$state->setDamage(2); // NORTH
					} elseif (!$west && !$east && !$north && $south) {
						$state->setDamage(8); // SOUTH
					} elseif ($west && !$east && $north && !$south) {
						$state->setDamage(1); // NORTH_WEST
					} elseif (!$west && $east && $north && !$south) {
						$state->setDamage(3); // NORTH_EAST
					} elseif ($west && !$east && !$north && $south) {
						$state->setDamage(7); // SOUTH_WEST
					} elseif (!$west && $east && !$north && $south) {
						$state->setDamage(9); // SOUTH_EAST
					} else {
						$state->setDamage(5); // CENTER
					}

					$this->placeMushroomBlock($level, $blockPos, $state);
				}
			}
		}
	}

	protected function getTreeRadiusForHeight(int $trunkHeight, int $treeHeight, int $leafRadius, int $yo) : int {
		return $yo <= 3 ? 0 : $leafRadius;
	}
}
