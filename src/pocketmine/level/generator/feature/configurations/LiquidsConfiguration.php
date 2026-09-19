<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\configurations;

use pocketmine\block\Block;

class LiquidsConfiguration implements FeatureConfiguration {
	/**
	 * @param Block[] $acceptedBlocks
	 */
	public function __construct(
		public Block $state,
		public bool $needsBlockBelow,
		public int $rockAmount,
		public int $holeAmount,
		public array $acceptedBlocks
	){}
}
