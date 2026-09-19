<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\ColorBlockMetaHelper;

class StainedGlass extends Glass
{
	protected $id = self::STAINED_GLASS;

	public function getName() : string
	{
		return ColorBlockMetaHelper::getColorFromMeta($this->getVariant()) . " Stained Glass";
	}
}
