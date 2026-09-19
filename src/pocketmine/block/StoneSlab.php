<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class StoneSlab extends Slab
{
	public const STONE = 0;
	public const SANDSTONE = 1;
	public const WOODEN = 2;
	public const COBBLESTONE = 3;
	public const BRICK = 4;
	public const STONE_BRICK = 5;
	public const QUARTZ = 6;
	public const NETHER_BRICK = 7;

	protected $id = self::STONE_SLAB;

	public function getDoubleSlabId() : int
	{
		return self::DOUBLE_STONE_SLAB;
	}

	public function getHardness() : float
	{
		return 2;
	}

	public function getName() : string
	{
		static $names = [
			self::STONE => "Stone",
			self::SANDSTONE => "Sandstone",
			self::WOODEN => "Wooden",
			self::COBBLESTONE => "Cobblestone",
			self::BRICK => "Brick",
			self::STONE_BRICK => "Stone Brick",
			self::QUARTZ => "Quartz",
			self::NETHER_BRICK => "Nether Brick"
		];
		return ($this->isTop() ? "Upper " : "") . ($names[$this->getVariant()] ?? "") . " Slab";
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
