<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class GlowingObsidian extends Solid
{
	protected $id = self::GLOWING_OBSIDIAN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Glowing Obsidian";
	}

	public function getLightLevel() : int
	{
		return 12;
	}

	public function getHardness() : float
	{
		return 10;
	}

	public function getBlastResistance() : float
	{
		return 50;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_DIAMOND;
	}
}
