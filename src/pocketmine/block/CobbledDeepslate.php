<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class CobbledDeepslate extends Solid
{
	protected $id = self::COBBLED_DEEPSLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Cobbled Deepslate";
	}

	public function getHardness() : float
	{
		return 3.5;
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
}
