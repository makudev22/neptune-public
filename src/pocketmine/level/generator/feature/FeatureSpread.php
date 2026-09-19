<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\utils\Random;

class FeatureSpread {
	public function __construct(
		public int $base,
		public int $spread = 0
	){}

	public function getCount(Random $random) : int {
		return $this->spread === 0 ? $this->base : $this->base + $random->nextBoundedInt($this->spread + 1);
	}

	public function equals(FeatureSpread $spread) : bool {
		if ($this === $spread) {
			return true;
		} elseif ($this instanceof $spread) {
			return $this->base == $spread->base && $this->spread == $spread->spread;
		} else {
			return false;
		}
	}
}
