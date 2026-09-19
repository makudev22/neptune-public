<?php


declare(strict_types=1);

namespace pocketmine\block;

class CrimsonDoubleSlab extends DoubleWoodenSlab
{
	protected $id = self::CRIMSON_DOUBLE_SLAB;

	public function getSlabId() : int
	{
		return self::CRIMSON_SLAB;
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
