<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\ColorBlockMetaHelper;
use pocketmine\item\TieredTool;

class Concrete extends Solid
{
	protected $id = Block::CONCRETE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return ColorBlockMetaHelper::getColorFromMeta($this->getVariant()) . " Concrete";
	}

	public function getHardness() : float
	{
		return 1.8;
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
