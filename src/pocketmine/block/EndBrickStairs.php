<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class EndBrickStairs extends Stair
{
	protected $id = self::END_BRICK_STAIRS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}

	public function getHardness() : float
	{
		return 0.8;
	}

	public function getBlastResistance() : float
	{
		return 4.0;
	}

	public function getName() : string
	{
		return "End Stone Brick Stairs";
	}
}
