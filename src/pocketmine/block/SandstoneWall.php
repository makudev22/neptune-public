<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class SandstoneWall extends Wall {
	public function getToolType() : int{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int{
		return TieredTool::TIER_WOODEN;
	}

	public function getBlastResistance() : float{
		return 4;
	}

	public function getHardness() : float{
		return 0.8;
	}
}
