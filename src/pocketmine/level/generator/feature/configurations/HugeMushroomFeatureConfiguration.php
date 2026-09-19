<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\stateproviders\BlockStateProvider;

class HugeMushroomFeatureConfiguration implements FeatureConfiguration {
	public function __construct(
		public BlockStateProvider $capProvider,
		public BlockStateProvider $stemProvider,
		public int $foliageRadius,
	){}
}
