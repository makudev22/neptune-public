<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class MonsterRoomPlacement extends PlacementModifier {

	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array{
		return [
			new Vector3(
				$origin->getX() + $random->nextBoundedInt(16) + 8,
				$random->nextBoundedInt(128),
				$origin->getZ() + $random->nextBoundedInt(16) + 8
			)
		];
	}
}
