<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\stateproviders;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class RandomizedIntStateProvider extends BlockStateProvider {
	public function __construct(
		protected BlockStateProvider $source,
		protected RandomizedIntStateSetter $setter
	){}

	public function type() : BlockStateProviderType{
		return BlockStateProviderType::RANDOMIZED_INT_STATE_PROVIDER;
	}

	public function getState(Random $random, Vector3 $pos) : Block {
		$unmodifiedState = $this->source->getState($random, $pos);
		$unmodifiedState->setDamage($this->setter->set($unmodifiedState->getDamage(), $random));
		return clone $unmodifiedState;
	}
}
