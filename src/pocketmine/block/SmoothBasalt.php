<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class SmoothBasalt extends Solid
{
	protected $id = self::SMOOTH_BASALT;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Smooth Basalt";
	}

	public function getHardness() : float
	{
		return 1.25;
	}

	public function getBlastResistance() : float
	{
		return 21;
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
