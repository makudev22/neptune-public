<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\Feature;

class MultipleRandomFeatureConfiguration implements FeatureConfiguration {
	/**
	 * @param ConfiguredRandomFeatureList[] $features
	 */
	public function __construct(
		public array $features,
		public Feature $defaultFeature
	){}
}
