<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class StoneBricks extends Solid
{
	public const NORMAL = 0;
	public const MOSSY = 1;
	public const CRACKED = 2;
	public const CHISELED = 3;

	protected $id = self::STONE_BRICKS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
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

	public function getName() : string
	{
		static $names = [
			self::NORMAL => "Stone Bricks",
			self::MOSSY => "Mossy Stone Bricks",
			self::CRACKED => "Cracked Stone Bricks",
			self::CHISELED => "Chiseled Stone Bricks"
		];
		return $names[$this->getVariant()] ?? "Unknown";
	}
}
