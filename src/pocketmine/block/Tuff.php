<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\TieredTool;

class Tuff extends Solid
{
	protected $id = self::TUFF;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Tuff";
	}

	public function getHardness() : float
	{
		return 1.5;
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
