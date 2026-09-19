<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class Prismarine extends Solid
{
	public const NORMAL = 0;
	public const DARK = 1;
	public const BRICKS = 2;

	protected $id = self::PRISMARINE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 1.5;
	}

	public function getName() : string
	{
		static $names = [
			self::NORMAL => "Prismarine",
			self::DARK => "Dark Prismarine",
			self::BRICKS => "Prismarine Bricks"
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
