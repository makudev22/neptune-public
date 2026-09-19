<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class IronTrapdoor extends Trapdoor
{
	public function getHardness() : float
	{
		return 5;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}

	public function getFuelTime() : int
	{
		return 0; //TODO: remove this hack on 4.0
	}
}
