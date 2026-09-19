<?php


declare(strict_types=1);

namespace pocketmine\block;

class WoodenTrapdoor extends Trapdoor
{
	public function getHardness() : float
	{
		return 3;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function getFuelTime() : int
	{
		if ($this->id === BlockIds::CRIMSON_TRAPDOOR || $this->id === BlockIds::WARPED_TRAPDOOR) {
			return 0;
		}

		return 300;
	}
}
