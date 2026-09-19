<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooMosaicSlab extends BambooSlab
{
	protected $id = self::BAMBOO_MOSAIC_SLAB;

	public function getDoubleSlabId() : int
	{
		return self::BAMBOO_MOSAIC_DOUBLE_SLAB;
	}

	public function getName() : string
	{
		return ($this->isTop() ? "Upper " : "") . "Bamboo Mosaic Slab";
	}
}
