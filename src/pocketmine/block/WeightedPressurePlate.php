<?php


declare(strict_types=1);

namespace pocketmine\block;

abstract class WeightedPressurePlate extends PressurePlate {

	public function getHardness() : float
	{
		return 0.5;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getDeactivationDelayTicks() : int {
		return 10;
	}
}
