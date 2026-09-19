<?php


declare(strict_types=1);

namespace pocketmine\block;

class WoodenDoor extends Door
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
		return 200;
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
