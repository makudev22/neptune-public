<?php


declare(strict_types=1);

namespace pocketmine\block;

class WoodenPressurePlate extends PressurePlate{
	public function getFuelTime() : int{
		return 300;
	}

	public function getHardness() : float{
		return 0.5;
	}

	public function getToolType() : int{
		return BlockToolType::TYPE_AXE;
	}

	public function getDeactivationDelayTicks() : int {
		return 20;
	}
}
