<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\template;

use pocketmine\block\Block;
use pocketmine\utils\Random;

class BlockClassTest extends RuleTest {

	public function __construct(
		private string $blockClass
	){}

	public function test(Block $state, Random $random) : bool {
		return $state instanceof $this->blockClass;
	}

	protected function getType() : RuleTestType {
		return RuleTestType::BLOCK_CLASS_TEST;
	}

}
