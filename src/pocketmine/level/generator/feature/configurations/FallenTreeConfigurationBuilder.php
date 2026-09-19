<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\level\generator\feature\stateproviders\BlockStateProvider;
use pocketmine\level\generator\feature\treedecorators\TreeDecorator;
use pocketmine\utils\valueproviders\IntProvider;

class FallenTreeConfigurationBuilder {

	public BlockStateProvider $trunkProvider;
	private IntProvider $logLength;
	/** @var TreeDecorator[] */
	private array $stumpDecorators = [];
	/** @var TreeDecorator[] */
	private array $logDecorators = [];

	public function __construct(
		BlockStateProvider $trunkProvider,
		IntProvider $logLength
	){
		$this->trunkProvider = $trunkProvider;
		$this->logLength = $logLength;
	}

	/**
	 * @param TreeDecorator[] $stumpDecorators
	 */
	public function stumpDecorators(array $stumpDecorators) : FallenTreeConfigurationBuilder{
		$this->stumpDecorators = $stumpDecorators;
		return $this;
	}

	/**
	 * @param TreeDecorator[] $logDecorators
	 */
	public function logDecorators(array $logDecorators) : FallenTreeConfigurationBuilder{
		$this->logDecorators = $logDecorators;
		return $this;
	}

	public function build() : FallenTreeConfiguration{
		return new FallenTreeConfiguration(
			$this->trunkProvider,
			$this->logLength,
			$this->stumpDecorators,
			$this->logDecorators
		);
	}
}
