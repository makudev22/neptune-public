<?php


declare(strict_types=1);

namespace pocketmine\block;

class PrismarineBricksStairs extends PrismarineStairs
{
	protected $id = self::PRISMARINE_BRICKS_STAIRS;

	public function getName() : string
	{
		return "Prismarine Bricks Stairs";
	}
}
