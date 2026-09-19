<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooMosaicStairs extends BambooStairs
{
	protected $id = self::BAMBOO_MOSAIC_STAIRS;

	public function getName() : string
	{
		return "Bamboo Stairs";
	}
}
