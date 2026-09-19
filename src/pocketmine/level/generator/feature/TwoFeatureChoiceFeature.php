<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\level\generator\feature\configurations\TwoFeatureChoiceConfiguration;

class TwoFeatureChoiceFeature extends Feature {

	public function __construct(
		public TwoFeatureChoiceConfiguration $config
	){}

	public function place(FeaturePlaceContext $context) : bool{
		$level = $context->level();
		$origin = $context->origin();
		$random = $context->random();
		$config = $this->config;

		$context = new FeaturePlaceContext($level, $random, $origin);
		return $random->nextBoolean() ?
			$config->featureTrue->place($context) :
			$config->featureFalse->place($context);
	}
}
