<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\template;

use pocketmine\block\Block;
use pocketmine\utils\Random;

class AlwaysTrueTest extends RuleTest {

	public function test(Block $state, Random $random) : bool {
		return true;
	}

	protected function getType() : RuleTestType {
		return RuleTestType::ALWAYS_TRUE_TEST;
	}

}
