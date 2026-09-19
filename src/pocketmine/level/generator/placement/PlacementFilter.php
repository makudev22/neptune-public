<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;

abstract class PlacementFilter extends PlacementModifier {

	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array{
		return $this->shouldPlace($context, $random, $origin) ? [$origin] : [];
	}

	abstract public function shouldPlace(PlacementContext $context, Random $random, Vector3 $origin) : bool;
}
