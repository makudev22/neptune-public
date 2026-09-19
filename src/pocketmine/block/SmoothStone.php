<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\TieredTool;

class SmoothStone extends Solid
{
	protected $id = self::SMOOTH_STONE;

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
		return "Smooth Stone";
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [ItemFactory::get(Block::COBBLESTONE)];
	}

	public function isAffectedBySilkTouch() : bool
	{
		return true;
	}
}
