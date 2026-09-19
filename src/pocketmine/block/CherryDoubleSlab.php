<?php


declare(strict_types=1);

namespace pocketmine\block;

class CherryDoubleSlab extends DoubleWoodenSlab
{
	protected $id = self::CHERRY_DOUBLE_SLAB;

	public function getSlabId() : int
	{
		return self::CHERRY_SLAB;
	}
}
