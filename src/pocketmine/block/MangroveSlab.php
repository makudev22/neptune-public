<?php


declare(strict_types=1);

namespace pocketmine\block;

class MangroveSlab extends WoodenSlab
{
	protected $id = self::MANGROVE_SLAB;

	public function getDoubleSlabId() : int
	{
		return self::MANGROVE_DOUBLE_SLAB;
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
		return ($this->isTop() ? "Upper " : "") . "Mangrove Slab";
	}
}
