<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class GraniteStairs extends Stair
{
	protected $id = self::GRANITE_STAIRS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}

	public function getBlastResistance() : float
	{
		return 6;
	}

	public function getHardness() : float
	{
		return 1.5;
	}

	public function getName() : string
	{
		return "Granite Stairs";
	}
}
