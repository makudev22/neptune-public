<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\template;

use pocketmine\block\Block;
use pocketmine\utils\Random;

abstract class RuleTest {

	abstract public function test(Block $state, Random $random) : bool;

	abstract protected function getType() : RuleTestType;

}
