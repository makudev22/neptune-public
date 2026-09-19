<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooMosaicDoubleSlab extends BambooDoubleSlab
{
	protected $id = self::BAMBOO_MOSAIC_DOUBLE_SLAB;

	public function getSlabId() : int
	{
		return self::BAMBOO_MOSAIC_SLAB;
	}
}
