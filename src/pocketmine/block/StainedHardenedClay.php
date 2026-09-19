<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\ColorBlockMetaHelper;

class StainedHardenedClay extends HardenedClay
{
	protected $id = self::STAINED_CLAY;

	public function getName() : string
	{
		return ColorBlockMetaHelper::getColorFromMeta($this->getVariant()) . " Stained Clay";
	}
}
