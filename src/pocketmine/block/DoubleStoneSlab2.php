<?php


declare(strict_types=1);

namespace pocketmine\block;

class DoubleStoneSlab2 extends DoubleStoneSlab
{
	protected $id = self::DOUBLE_STONE_SLAB2;

	public function getSlabId() : int
	{
		return self::STONE_SLAB2;
	}
}
