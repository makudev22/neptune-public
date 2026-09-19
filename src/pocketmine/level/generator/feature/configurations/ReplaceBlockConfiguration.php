<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\block\Block;

class ReplaceBlockConfiguration implements FeatureConfiguration {
	public function __construct(
		public Block $target,
		public Block $state
	){}
}
