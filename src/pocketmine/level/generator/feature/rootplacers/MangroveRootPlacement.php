<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\rootplacers;

use pocketmine\block\Block;
use pocketmine\level\generator\feature\stateproviders\BlockStateProvider;

class MangroveRootPlacement{
	/**
	 * @param Block[] $canGrowThrough
	 * @param Block[] $muddyRootsIn
	 */
	public function __construct(
		private array $canGrowThrough,
		private array $muddyRootsIn,
		private BlockStateProvider $muddyRootsProvider,
		private int $maxRootWidth,
		private int $maxRootLength,
		private float $randomSkewChance
	){}

	public function canGrowThrough() : array{
		return $this->canGrowThrough;
	}

	public function muddyRootsIn() : array{
		return $this->muddyRootsIn;
	}

	public function muddyRootsProvider() : BlockStateProvider{
		return $this->muddyRootsProvider;
	}

	public function maxRootWidth() : int{
		return $this->maxRootWidth;
	}

	public function maxRootLength() : int{
		return $this->maxRootLength;
	}

	public function randomSkewChance() : float{
		return $this->randomSkewChance;
	}
}
