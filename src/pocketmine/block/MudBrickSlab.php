<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class MudBrickSlab extends Slab
{
	protected $id = self::MUD_BRICK_SLAB;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return ($this->isTop() ? "Upper " : "") . "Mud Brick Slab";
	}

	public function getHardness() : float
	{
		return 1.5;
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

	public function getDoubleSlabId() : int
	{
		return self::MUD_BRICK_DOUBLE_SLAB;
	}

	public function getVariantBitmask() : int
	{
		return 0x00;
	}

	public function getTopBitmask() : int
	{
		return 0x01;
	}
}
