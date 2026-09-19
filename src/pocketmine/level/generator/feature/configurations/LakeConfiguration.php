<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\stateproviders\BlockStateProvider;

class LakeConfiguration implements FeatureConfiguration {
	public function __construct(
		public BlockStateProvider $fluid
	){}

}
