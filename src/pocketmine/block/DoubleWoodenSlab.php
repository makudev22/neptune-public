<?php


declare(strict_types=1);

namespace pocketmine\block;

class DoubleWoodenSlab extends DoubleSlab
{
	protected $id = self::DOUBLE_WOODEN_SLAB;

	public function getSlabId() : int
	{
		return self::WOODEN_SLAB;
	}

	public function getHardness() : float
	{
		return 2;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function getFlameEncouragement() : int
	{
		return 5;
	}

	public function getFlammability() : int
	{
		return 20;
	}
}
