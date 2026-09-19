<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\trunkplacers;

use pocketmine\math\Vector3;

class FoliageCoords {
	private FoliageAttachment $attachment;

	public function __construct(
		Vector3 $pos,
		private int $branchBase
	) {
		$this->attachment = new FoliageAttachment($pos, 0, false);
	}

	public function attachment() : FoliageAttachment {
		return $this->attachment;
	}

	public function getBranchBase() : int {
		return $this->branchBase;
	}
}
