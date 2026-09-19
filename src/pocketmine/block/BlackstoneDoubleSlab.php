<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class BlackstoneDoubleSlab extends DoubleSlab
{
	protected int $slabId;

	public function __construct(int $id, int $meta, int $slabId)
	{
		$this->id = $id;
		$this->meta = $meta;
		$this->slabId = $slabId;
	}

	public function getSlabId() : int
	{
		return $this->slabId;
	}

	public function getHardness() : float
	{
		return 1.5;
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
