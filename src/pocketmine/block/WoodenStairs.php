<?php


declare(strict_types=1);

namespace pocketmine\block;

class WoodenStairs extends Stair
{
	public function getHardness() : float
	{
		return 2;
	}

	public function getBlastResistance() : float
	{
		return 15;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function getFlameEncouragement() : int
	{
		return 5;
	}

	public function getFlammability() : int
	{
		return 20;
	}
}
