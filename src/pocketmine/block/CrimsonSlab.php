<?php


declare(strict_types=1);

namespace pocketmine\block;

class CrimsonSlab extends WoodenSlab
{
	protected $id = self::CRIMSON_SLAB;

	public function getDoubleSlabId() : int
	{
		return self::CRIMSON_DOUBLE_SLAB;
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
		return ($this->isTop() ? "Upper " : "") . "Crimson Slab";
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
