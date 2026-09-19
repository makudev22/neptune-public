<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\block\Block;
use pocketmine\level\generator\feature\template\RuleTest;

class OreConfiguration implements FeatureConfiguration {
	public function __construct(
		public RuleTest $target,
		public Block $state,
		public int $size
	){}
}
