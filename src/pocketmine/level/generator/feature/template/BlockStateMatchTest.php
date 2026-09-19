<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\template;

use pocketmine\block\Block;
use pocketmine\utils\Random;

class BlockStateMatchTest extends RuleTest {

	public function __construct(
		private Block $block
	){}

	public function test(Block $state, Random $random) : bool {
		return $state->isSameState($this->block);
	}

	protected function getType() : RuleTestType {
		return RuleTestType::BLOCKSTATE_TEST;
	}

}
