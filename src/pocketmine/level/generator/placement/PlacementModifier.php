<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;

abstract class PlacementModifier {

	/**
	 * @return Vector3[]
	 */
	abstract public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array;

}
