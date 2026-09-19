<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\TieredTool;
use pocketmine\math\Vector3;
use pocketmine\Player;

class GlazedTerracotta extends Solid
{
	public function getHardness() : float
	{
		return 1.4;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		if ($player !== null) {
			$faces = [
				0 => 4,
				1 => 3,
				2 => 5,
				3 => 2
			];
			$this->meta = $faces[(~($player->getDirection() - 1)) & 0x03];
		}

		$this->getLevel()->setBlock($blockReplace, $this, true, true);
		return true;
	}

	public function getVariantBitmask() : int
	{
		return 0;
	}
}
