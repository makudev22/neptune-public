<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooMosaic extends BambooPlanks
{
	protected $id = self::BAMBOO_MOSAIC;

	public function getName() : string
	{
		return "Bamboo Mosaic";
	}
}
