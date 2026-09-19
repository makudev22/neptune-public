<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class GoldOre extends Solid
{
	protected $id = self::GOLD_ORE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Gold Ore";
	}

	public function getHardness() : float
	{
		return 3;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_IRON;
	}
}
