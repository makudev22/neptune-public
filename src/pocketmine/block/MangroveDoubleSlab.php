<?php


declare(strict_types=1);

namespace pocketmine\block;

class MangroveDoubleSlab extends DoubleWoodenSlab
{
	protected $id = self::MANGROVE_DOUBLE_SLAB;

	public function getSlabId() : int
	{
		return self::MANGROVE_SLAB;
	}
}
