<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\Feature;

class TwoFeatureChoiceConfiguration implements FeatureConfiguration {
	public function __construct(
		public Feature $featureTrue,
		public Feature $featureFalse
	){}
}
