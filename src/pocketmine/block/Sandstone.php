<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class Sandstone extends Solid
{
	public const NORMAL = 0;
	public const CHISELED = 1;
	public const SMOOTH = 2;

	protected $id = self::SANDSTONE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 0.8;
	}

	public function getName() : string
	{
		static $names = [
			self::NORMAL => "Sandstone",
			self::CHISELED => "Chiseled Sandstone",
			self::SMOOTH => "Smooth Sandstone"
		];
		return $names[$this->getVariant()] ?? "Unknown";
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
		return 0x03;
	}
}
