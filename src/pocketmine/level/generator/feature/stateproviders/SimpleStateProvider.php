<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\stateproviders;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class SimpleStateProvider extends BlockStateProvider {
	public function __construct(
		protected Block $state
	){}

	public function type() : BlockStateProviderType{
		return BlockStateProviderType::SIMPLE_STATE_PROVIDER;
	}

	public function getState(Random $random, Vector3 $pos) : Block {
		return clone $this->state;
	}
}
