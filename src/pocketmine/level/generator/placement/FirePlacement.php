<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\level\generator\feature\FeatureSpread;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class FirePlacement extends PlacementModifier {
	public function __construct(
		private FeatureSpread $spread
	){}

	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array{
		$list = [];

		for($i = 0; $i < $random->nextBoundedInt($random->nextBoundedInt($this->spread->getCount($random)) + 1) + 1; ++$i) {
			$x = $random->nextBoundedInt(16) + $origin->getX();
			$z = $random->nextBoundedInt(16) + $origin->getZ();
			$y = $random->nextBoundedInt(120) + 4;
			$list[] = new Vector3($x, $y, $z);
		}

		return $list;
	}
}
