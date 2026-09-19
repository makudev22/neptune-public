<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\Feature;

class ConfiguredRandomFeatureList implements FeatureConfiguration {
	public function __construct(
		public Feature $feature,
		public float $chance
	){}
}
