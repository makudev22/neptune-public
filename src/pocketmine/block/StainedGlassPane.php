<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\ColorBlockMetaHelper;

class StainedGlassPane extends GlassPane
{
	protected $id = self::STAINED_GLASS_PANE;

	public function getName() : string
	{
		return ColorBlockMetaHelper::getColorFromMeta($this->getVariant()) . " Stained Glass Pane";
	}
}
