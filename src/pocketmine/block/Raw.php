<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

abstract class Raw extends Solid
{

	public function getHardness() : float
	{
		return 5;
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
		return TieredTool::TIER_STONE;
	}
}
