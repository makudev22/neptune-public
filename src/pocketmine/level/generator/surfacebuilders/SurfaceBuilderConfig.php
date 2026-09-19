<?php


declare(strict_types=1);

namespace pocketmine\level\generator\surfacebuilders;

use pocketmine\block\Block;

class SurfaceBuilderConfig {
	public function __construct(
		private Block $topMaterial,
		private Block $underMaterial,
		private Block $underWaterMaterial
	){}

	public function getTop() : Block {
		return $this->topMaterial;
	}

	public function getUnder() : Block {
		return $this->underMaterial;
	}

	public function getUnderWater() : Block {
		return $this->underWaterMaterial;
	}
}
