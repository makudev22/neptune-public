<?php


declare(strict_types=1);

namespace pocketmine\block\utils;

use pocketmine\block\BlockToolType;
use pocketmine\item\TieredTool;

trait BushTrait {
	public function canBeReplaced() : bool{
		return true;
	}

	public function getToolType() : int{
		return BlockToolType::TYPE_SHEARS;
	}

	public function getToolHarvestLevel() : int{
		return TieredTool::TIER_WOODEN;
	}

	public function getFlameEncouragement() : int{
		return 60;
	}

	public function getFlammability() : int{
		return 100;
	}
}
