<?php


declare(strict_types=1);

namespace pocketmine\level\generator\settings;

class StructureSpreadSettings {
	public function __construct(
		private int $distance,
		private int $spread,
		private int $count
	){}

	public function getDistance() : int {
		return $this->distance;
	}

	public function getSpread() : int {
		return $this->spread;
	}

	public function getCount() : int {
		return $this->count;
	}
}
