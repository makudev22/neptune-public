<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature;

use pocketmine\level\generator\feature\configurations\ReplaceBlockConfiguration;

class ReplaceBlockFeature extends Feature {

	public function __construct(
		public ReplaceBlockConfiguration $config
	){}

	public function place(FeaturePlaceContext $context) : bool{
		$origin = $context->origin();
		$level = $context->level();
		$config = $this->config;

		if ($level->getBlockAt($origin->getFloorX(), $origin->getFloorY(), $origin->getFloorZ())->isSameType($config->target)) {
			$level->setBlockAt($origin->getFloorX(), $origin->getFloorY(), $origin->getFloorZ(), $config->state);
		}

		return true;
	}
}
