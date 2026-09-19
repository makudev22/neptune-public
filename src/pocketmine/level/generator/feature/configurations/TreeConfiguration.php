<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\featuresize\FeatureSize;
use pocketmine\level\generator\feature\foliageplacers\FoliagePlacer;
use pocketmine\level\generator\feature\rootplacers\RootPlacer;
use pocketmine\level\generator\feature\stateproviders\BlockStateProvider;
use pocketmine\level\generator\feature\treedecorators\TreeDecorator;
use pocketmine\level\generator\feature\trunkplacers\TrunkPlacer;

class TreeConfiguration implements FeatureConfiguration {
	/**
	 * @param TreeDecorator[] $decorators
	 */
	public function __construct(
		public BlockStateProvider $trunkProvider,
		public BlockStateProvider $dirtProvider,
		public TrunkPlacer $trunkPlacer,
		public BlockStateProvider $foliageProvider,
		public FoliagePlacer $foliagePlacer,
		public ?RootPlacer $rootPlacer,
		public FeatureSize $minimumSize,
		public array $decorators,
		public bool $ignoreVines,
		public bool $forceDirt
	){}

}
