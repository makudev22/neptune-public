<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class InSquarePlacement extends PlacementModifier {

	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array{
		$x = $random->nextBoundedInt(16) + $origin->getX();
		$z = $random->nextBoundedInt(16) + $origin->getZ();
		return [new Vector3($x, $origin->getY(), $z)];
	}
}
