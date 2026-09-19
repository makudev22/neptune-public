<?php


declare(strict_types=1);

namespace pocketmine\block;

class SmoothRedSandstoneStairs extends SandstoneStairs
{
	protected $id = self::SMOOTH_RED_SANDSTONE_STAIRS;

	public function getName() : string
	{
		return "Smooth Red Sandstone stairs";
	}
}
