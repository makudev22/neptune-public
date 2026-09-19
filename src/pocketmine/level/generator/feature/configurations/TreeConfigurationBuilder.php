<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\level\generator\feature\featuresize\FeatureSize;
use pocketmine\level\generator\feature\foliageplacers\FoliagePlacer;
use pocketmine\level\generator\feature\rootplacers\RootPlacer;
use pocketmine\level\generator\feature\stateproviders\BlockStateProvider;
use pocketmine\level\generator\feature\treedecorators\TreeDecorator;
use pocketmine\level\generator\feature\trunkplacers\TrunkPlacer;

class TreeConfigurationBuilder {

	public BlockStateProvider $trunkProvider;
	private TrunkPlacer $trunkPlacer;
	public BlockStateProvider $foliageProvider;
	private FoliagePlacer $foliagePlacer;
	private ?RootPlacer $rootPlacer = null;
	private BlockStateProvider $dirtProvider;
	private FeatureSize $minimumSize;
	/** @var TreeDecorator[] */
	private array $decorators = [];
	private bool $ignoreVines = false;
	private bool $forceDirt = false;

	public function __construct(
		BlockStateProvider $trunkProvider,
		TrunkPlacer $trunkPlacer,
		BlockStateProvider $foliageProvider,
		FoliagePlacer $foliagePlacer,
		?RootPlacer $rootPlacer,
		FeatureSize $minimumSize
	){
		$this->trunkProvider = $trunkProvider;
		$this->trunkPlacer = $trunkPlacer;
		$this->foliageProvider = $foliageProvider;
		$this->dirtProvider = BlockStateProvider::simple(BlockFactory::get(BlockIds::DIRT));
		$this->foliagePlacer = $foliagePlacer;
		$this->rootPlacer = $rootPlacer;
		$this->minimumSize = $minimumSize;
	}

	public function dirt(BlockStateProvider $dirtProvider) : TreeConfigurationBuilder{
		$this->dirtProvider = $dirtProvider;
		return $this;
	}

	/**
	 * @param TreeDecorator[] $decorators
	 */
	public function decorators(array $decorators) : TreeConfigurationBuilder{
		$this->decorators = $decorators;
		return $this;
	}

	public function ignoreVines() : TreeConfigurationBuilder{
		$this->ignoreVines = true;
		return $this;
	}

	public function forceDirt() : TreeConfigurationBuilder{
		$this->forceDirt = true;
		return $this;
	}

	public function build() : TreeConfiguration{
		return new TreeConfiguration(
			$this->trunkProvider,
			$this->dirtProvider,
			$this->trunkPlacer,
			$this->foliageProvider,
			$this->foliagePlacer,
			$this->rootPlacer,
			$this->minimumSize,
			$this->decorators,
			$this->ignoreVines,
			$this->forceDirt
		);
	}
}
