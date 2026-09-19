<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\block\Block;

class BlockStateConfiguration implements FeatureConfiguration {
	public function __construct(
		public Block $state
	){}

}
