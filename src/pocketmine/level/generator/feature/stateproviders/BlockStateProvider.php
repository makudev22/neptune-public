<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\stateproviders;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

abstract class BlockStateProvider {

	public static function simple(Block $block) : BlockStateProvider {
		return new SimpleStateProvider($block);
	}

	abstract public function type() : BlockStateProviderType;

	abstract public function getState(Random $random, Vector3 $pos) : Block;

}
