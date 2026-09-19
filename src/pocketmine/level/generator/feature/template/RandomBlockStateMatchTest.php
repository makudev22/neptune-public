<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\template;

use pocketmine\block\Block;
use pocketmine\utils\Random;

class RandomBlockStateMatchTest extends RuleTest {

	public function __construct(
		private Block $block,
		private float $probability
	){}

	public function test(Block $state, Random $random) : bool {
		return $state->isSameState($this->block) && $random->nextFloat() < $this->probability;
	}

	protected function getType() : RuleTestType {
		return RuleTestType::RANDOM_BLOCKSTATE_TEST;
	}

}
