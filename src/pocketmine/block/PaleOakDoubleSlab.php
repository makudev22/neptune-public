<?php


declare(strict_types=1);

namespace pocketmine\block;

class PaleOakDoubleSlab extends DoubleWoodenSlab
{
	protected $id = self::PALE_OAK_DOUBLE_SLAB;

	public function getSlabId() : int
	{
		return self::PALE_OAK_SLAB;
	}
}
