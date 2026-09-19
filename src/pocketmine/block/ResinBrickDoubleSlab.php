<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class ResinBrickDoubleSlab extends DoubleSlab
{
	protected $id = self::RESIN_BRICK_DOUBLE_SLAB;

	public function getSlabId() : int
	{
		return self::RESIN_BRICK_SLAB;
	}

	public function getHardness() : float
	{
		return 1.5;
	}

	public function getBlastResistance() : float
	{
		return 6;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}
}
