<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\level\generator\feature\configurations\MultipleRandomFeatureConfiguration;

class MultipleWithChanceRandomFeature extends Feature {

	public function __construct(
		public MultipleRandomFeatureConfiguration $config
	){}

	public function place(FeaturePlaceContext $context) : bool{
		$level = $context->level();
		$origin = $context->origin();
		$random = $context->random();
		$config = $this->config;

		foreach ($config->features as $randomFeatureList) {
			if ($random->nextFloat() < $randomFeatureList->chance) {
				return $randomFeatureList->feature->place(new FeaturePlaceContext($level, $random, $origin));
			}
		}

		return $config->defaultFeature->place(new FeaturePlaceContext($level, $random, $origin));
	}
}
