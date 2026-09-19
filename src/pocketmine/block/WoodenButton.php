<?php


declare(strict_types=1);

namespace pocketmine\block;

class WoodenButton extends Button
{
	public function getHardness() : float
	{
		return 0.5;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	protected function getActivationTime() : int
	{
		return 30;
	}
}
