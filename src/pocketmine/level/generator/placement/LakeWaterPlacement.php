<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class LakeWaterPlacement extends PlacementModifier {

	public function __construct(
		private int $chance
	){}

	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array{
		$generator = $context->getGenerator();

		if ($random->nextBoundedInt($this->chance) == 0) {
			$x = $random->nextBoundedInt(16) + $origin->getX();
			$z = $random->nextBoundedInt(16) + $origin->getZ();
			$y = $random->nextBoundedInt(($generator->getMaxBuildHeight()));
			return [new Vector3($x, $y, $z)];
		}

		return [];
	}
}
