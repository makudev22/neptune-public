<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;

abstract class RepeatingPlacement extends PlacementModifier {

	abstract protected function count(Random $random, Vector3 $origin) : int;

	/**
	 * @return Vector3[]
	 */
	public function getPositions(PlacementContext $context, Random $random, Vector3 $origin) : array {
		$positions = [];
		for ($i = 0; $i < $this->count($random, $origin); $i++) {
			$positions[] = $origin;
		}

		return $positions;
	}
}
