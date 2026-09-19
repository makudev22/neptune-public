<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;

abstract class AmethystBud extends Transparent
{

	public function getHardness() : float
	{
		return 1.5;
	}

	public function getBlastResistance() : float
	{
		return 1.5;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [];
	}

	protected function recalculateBoundingBox() : ?AxisAlignedBB
	{
		return null;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{
		if ($this->canSupportToFullSolid($this->getSide(Facing::opposite($face)))) {
			$this->meta = $face;
			return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}

		return false;
	}

	public function onNearbyBlockChange() : void
	{
		if (!$this->canSupportToFullSolid($this->getSide(Facing::opposite($this->meta)))) {
			$this->level->useBreakOn($this);
		}
	}

	public function getBoundingBox() : ?AxisAlignedBB
	{
		return null;
	}
}
