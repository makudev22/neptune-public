<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\block\Block;
use pocketmine\level\generator\feature\blockplacer\BlockPlacer;
use pocketmine\level\generator\feature\blocksupport\BlockSupport;
use pocketmine\level\generator\feature\stateproviders\BlockStateProvider;

class BlockClusterConfiguration implements FeatureConfiguration {
	/**
	 * @param Block[] $whitelist
	 * @param Block[] $blacklist
	 */
	public function __construct(
		public BlockStateProvider $stateProvider,
		public BlockPlacer $blockPlacer,
		public BlockSupport $supportBlock,
		public array $whitelist,
		public array $blacklist,
		public int $tryCount,
		public int $xSpread,
		public int $ySpread,
		public int $zSpread,
		public bool $isReplaceable,
		public bool $requiresWater
	){}
}
