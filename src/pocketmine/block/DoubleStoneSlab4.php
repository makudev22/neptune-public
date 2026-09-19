<?php


declare(strict_types=1);

namespace pocketmine\block;

class DoubleStoneSlab4 extends DoubleStoneSlab
{
	protected $id = self::DOUBLE_STONE_SLAB4;

	public function getSlabId() : int
	{
		return self::STONE_SLAB4;
	}
}
