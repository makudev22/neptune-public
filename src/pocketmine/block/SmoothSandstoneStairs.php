<?php


declare(strict_types=1);

namespace pocketmine\block;

class SmoothSandstoneStairs extends SandstoneStairs
{
	protected $id = self::SMOOTH_SANDSTONE_STAIRS;

	public function getName() : string
	{
		return "Smooth Sandstone Stairs";
	}
}
