<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooDoubleSlab extends DoubleWoodenSlab
{
	protected $id = self::BAMBOO_DOUBLE_SLAB;

	public function getSlabId() : int
	{
		return self::BAMBOO_SLAB;
	}
}
