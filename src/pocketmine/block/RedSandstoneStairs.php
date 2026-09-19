<?php


declare(strict_types=1);

namespace pocketmine\block;

class RedSandstoneStairs extends SandstoneStairs
{
	protected $id = self::RED_SANDSTONE_STAIRS;

	public function getName() : string
	{
		return "Red " . parent::getName();
	}
}
