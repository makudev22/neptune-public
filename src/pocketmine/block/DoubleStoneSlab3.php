<?php


declare(strict_types=1);

namespace pocketmine\block;

class DoubleStoneSlab3 extends DoubleStoneSlab
{
	protected $id = self::DOUBLE_STONE_SLAB3;

	public function getSlabId() : int
	{
		return self::STONE_SLAB3;
	}
}
