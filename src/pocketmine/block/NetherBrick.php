<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class NetherBrick extends Solid
{
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
		return 2;
	}
}
