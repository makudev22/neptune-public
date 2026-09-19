<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\stateproviders\BlockStateProvider;
use pocketmine\level\generator\feature\treedecorators\TreeDecorator;
use pocketmine\utils\valueproviders\IntProvider;

class FallenTreeConfiguration implements FeatureConfiguration {
	/**
	 * @param TreeDecorator[] $stumpDecorators
	 * @param TreeDecorator[] $logDecorators
	 */
	public function __construct(
		public BlockStateProvider $trunkProvider,
		public IntProvider $logLength,
		public array $stumpDecorators,
		public array $logDecorators,
	){}

}
