<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\template;

use pocketmine\block\Block;
use pocketmine\utils\Random;

class BlockMatchTest extends RuleTest {

	public function __construct(
		private Block $block
	){}

	public function test(Block $state, Random $random) : bool {
		return $state->isSameType($this->block);
	}

	protected function getType() : RuleTestType {
		return RuleTestType::BLOCK_TEST;
	}

}
