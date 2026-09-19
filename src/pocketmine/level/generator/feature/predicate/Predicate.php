<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\predicate;

use pocketmine\block\Block;

interface Predicate {

	public function is(Block $block) : bool;

}
