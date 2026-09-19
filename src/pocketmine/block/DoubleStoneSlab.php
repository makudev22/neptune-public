<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class DoubleStoneSlab extends DoubleSlab
{
	protected $id = self::DOUBLE_STONE_SLAB;

	public function getSlabId() : int
	{
		return self::STONE_SLAB;
	}

	public function getHardness() : float
	{
		return 2;
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
