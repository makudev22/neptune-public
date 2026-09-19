<?php


declare(strict_types=1);

namespace pocketmine\block;

class WarpedSlab extends WoodenSlab
{
	protected $id = self::WARPED_SLAB;

	public function getDoubleSlabId() : int
	{
		return self::WARPED_DOUBLE_SLAB;
	}

	public function getVariantBitmask() : int
	{
		return 0x00;
	}

	public function getTopBitmask() : int
	{
		return 0x01;
	}

	public function getName() : string
	{
		return ($this->isTop() ? "Upper " : "") . "Warped Slab";
	}

	public function getFuelTime() : int
	{
		return 0;
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
