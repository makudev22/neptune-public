<?php


declare(strict_types=1);

namespace pocketmine\block;

class WarpedFence extends WoodenFence
{
	protected $id = self::WARPED_FENCE;

	public function getHardness() : float
	{
		return 2;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function getName() : string
	{
		return "Warped Fence";
	}

	public function getFuelTime() : int
	{
		return 0;
	}

	public function getFlameEncouragement() : int
	{
		return 0;
	}

	public function getFlammability() : int
	{
		return 0;
	}
}
