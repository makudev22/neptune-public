<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\block\Air;
use pocketmine\block\Water;
use pocketmine\level\generator\feature\configurations\BlockClusterConfiguration;
use pocketmine\math\Facing;
use function count;

class RandomPatchFeature extends Feature {

	public function __construct(
		public BlockClusterConfiguration $config
	){}

	public function place(FeaturePlaceContext $context) : bool{
		$level = $context->level();
		$origin = $context->origin();
		$random = $context->random();
		$config = $this->config;

		$blockState = $config->stateProvider->getState($random, $origin);
		$placed = 0;
		for ($i = 0; $i < $config->tryCount; ++$i) {
			$mutable = $origin->add(
				$random->nextBoundedInt($config->xSpread + 1) - $random->nextBoundedInt($config->xSpread + 1),
				$random->nextBoundedInt($config->ySpread + 1) - $random->nextBoundedInt($config->ySpread + 1),
				$random->nextBoundedInt($config->zSpread + 1) - $random->nextBoundedInt($config->zSpread + 1)
			);
			$blockPos1 = $mutable->down();

			$blockState1 = $level->getBlockAt($blockPos1->getFloorX(), $blockPos1->getFloorY(), $blockPos1->getFloorZ());
			$target = $level->getBlockAt($mutable->getFloorX(), $mutable->getFloorY(), $mutable->getFloorZ());
			$isValidPosition = $config->supportBlock->isValidPosition($level, $mutable);
			$isBlacklisted = false;
			$isWhitelisted = true;
			foreach ($config->blacklist as $blackBlock) {
				if ($blockState1->isSameType($blackBlock)) {
					$isBlacklisted = true;
					break;
				}
			}
			if (count($config->whitelist) !== 0) {
				$isWhitelisted = false;
				foreach ($config->whitelist as $whitelist) {
					if ($blockState1->isSameType($whitelist)) {
						$isWhitelisted = true;
						break;
					}
				}
			}
			$hasWater = false;
			if ($config->requiresWater) {
				foreach (Facing::HORIZONTAL as $face) {
					$sidePos = $blockPos1->getSide($face);
					$sideBlock = $level->getBlockAt($sidePos->getFloorX(), $sidePos->getFloorY(), $sidePos->getFloorZ());
					if ($sideBlock instanceof Water) {
						$hasWater = true;
						break;
					}
				}
			} else {
				$hasWater = true; // Not required, so true
			}

			if (($target instanceof Air || ($config->isReplaceable && $target->canBeReplaced())) &&
				$isWhitelisted &&
				$isValidPosition &&
				!$isBlacklisted &&
				$hasWater
			) {
				$config->blockPlacer->place($level, $mutable, clone $blockState, $random);
				$placed++;
			}
		}

		return $placed > 0;
	}
}
