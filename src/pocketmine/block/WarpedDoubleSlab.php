<?php


declare(strict_types=1);

namespace pocketmine\block;

class WarpedDoubleSlab extends DoubleWoodenSlab
{
	protected $id = self::WARPED_DOUBLE_SLAB;

	public function getSlabId() : int
	{
		return self::WARPED_SLAB;
	}

	public function getFlameEncouragement() : int
	{
		return 0;
	}

	public function getFlammability() : int
	{
		return 0;
	}
}
