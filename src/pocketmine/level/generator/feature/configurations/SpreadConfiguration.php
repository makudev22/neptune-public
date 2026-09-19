<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\FeatureSpread;

class SpreadConfiguration implements FeatureConfiguration {
	public function __construct(
		public FeatureSpread $spread
	){}
}
