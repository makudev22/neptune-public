<?php


declare(strict_types=1);

namespace pocketmine\block;

class DarkPrismarineStairs extends PrismarineStairs
{
	protected $id = self::DARK_PRISMARINE_STAIRS;

	public function getName() : string
	{
		return "Dark Prismarine Stairs";
	}
}
