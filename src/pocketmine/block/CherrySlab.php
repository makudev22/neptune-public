<?php


declare(strict_types=1);

namespace pocketmine\block;

class CherrySlab extends WoodenSlab
{
	protected $id = self::CHERRY_SLAB;

	public function getDoubleSlabId() : int
	{
		return self::CHERRY_DOUBLE_SLAB;
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
		return ($this->isTop() ? "Upper " : "") . "Cherry Slab";
	}
}
