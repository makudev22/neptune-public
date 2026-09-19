<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class CryingObsidian extends Solid
{
	protected $id = self::CRYING_OBSIDIAN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Crying Obsidian";
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_DIAMOND;
	}

	public function getHardness() : float
	{
		return 35; //50 in PC
	}

	public function getBlastResistance() : float
	{
		return 6000;
	}

	public function getLightLevel() : int
	{
		return 10;
	}
}
