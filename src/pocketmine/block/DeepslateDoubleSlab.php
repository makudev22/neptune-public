<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class DeepslateDoubleSlab extends DoubleSlab
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
		return 3.5;
	}

	public function getBlastResistance() : float
	{
		return 30;
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
