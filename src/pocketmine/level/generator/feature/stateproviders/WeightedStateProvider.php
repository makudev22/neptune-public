<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\stateproviders;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use function count;

class WeightedStateProvider extends BlockStateProvider {
	/**
	 * @param Block[] $weightedList
	 */
	public function __construct(
		protected array $weightedList
	){}

	public function type() : BlockStateProviderType{
		return BlockStateProviderType::WEIGHTED_STATE_PROVIDER;
	}

	public function getState(Random $random, Vector3 $pos) : Block {
		return clone $this->weightedList[$random->nextRange(0, count($this->weightedList) - 1)];
	}
}
