<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\block\Block;
use pocketmine\level\generator\feature\FeatureSpread;

class SphereReplaceConfiguration implements FeatureConfiguration {
	/**
	 * @param Block[] $targets
	 */
	public function __construct(
		public Block $state,
		public FeatureSpread $radius,
		public int $halfHeight,
		public array $targets
	){}
}
