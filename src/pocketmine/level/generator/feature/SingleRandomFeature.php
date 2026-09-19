<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\level\generator\feature\configurations\SingleRandomConfiguration;
use function count;

class SingleRandomFeature extends Feature {

	public function __construct(
		public SingleRandomConfiguration $config
	){}

	public function place(FeaturePlaceContext $context) : bool{
		$level = $context->level();
		$origin = $context->origin();
		$random = $context->random();
		$config = $this->config;

		$feature = $config->features[$random->nextBoundedInt(count($config->features))];
		return $feature->place(new FeaturePlaceContext($level, $random, $origin));
	}
}
