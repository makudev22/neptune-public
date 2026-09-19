<?php


declare(strict_types=1);

namespace pocketmine\inventory\random;

class WeightedRandomItem {
	public function __construct(
		public int $itemWeight
	){}
}
