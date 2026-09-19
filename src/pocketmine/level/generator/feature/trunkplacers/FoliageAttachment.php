<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\trunkplacers;

use pocketmine\math\Vector3;

class FoliageAttachment {

	public function __construct(
		private Vector3 $pos,
		private int $radiusOffset,
		private bool $doubleTrunk
	){}

	public function pos() : Vector3 {
		return $this->pos;
	}

	public function radiusOffset() : int {
		return $this->radiusOffset;
	}

	public function doubleTrunk() : bool {
		return $this->doubleTrunk;
	}
}
