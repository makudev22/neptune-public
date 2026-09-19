<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\rootplacers;

use pocketmine\level\generator\feature\stateproviders\BlockStateProvider;

class AboveRootPlacement{
	public function __construct(
		private BlockStateProvider $aboveRootProvider,
		private float $aboveRootPlacementChance
	){}

	public function aboveRootProvider() : BlockStateProvider {
		return $this->aboveRootProvider;
	}

	public function aboveRootPlacementChance() : float {
		return $this->aboveRootPlacementChance;
	}
}
