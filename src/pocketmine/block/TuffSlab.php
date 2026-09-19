<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class TuffSlab extends Slab
{
	protected int $doubleSlabId;

	public function __construct(int $id, int $meta, string $name, int $doubleSlabId)
	{
		$this->id = $id;
		$this->meta = $meta;
		$this->fallbackName = $name;
		$this->doubleSlabId = $doubleSlabId;
	}

	public function getDoubleSlabId() : int
	{
		return $this->doubleSlabId;
	}

	public function getHardness() : float
	{
		return 1.5;
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
		return TieredTool::TIER_WOODEN;
	}

	public function getVariantBitmask() : int
	{
		return 0x00;
	}

	public function getTopBitmask() : int
	{
		return 0x01;
	}

	public function getName() : string
	{
		return ($this->isTop() ? "Upper " : "") . $this->fallbackName;
	}
}
