<?php


declare(strict_types=1);

namespace pocketmine\level\generator\placement;

use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class RarityFilter extends PlacementFilter {
	public function __construct(
		private int $chance
	){}

	public function shouldPlace(PlacementContext $context, Random $random, Vector3 $origin) : bool{
		return $random->nextBoundedInt($this->chance) === 0;
	}
}
