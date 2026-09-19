<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;

class CopperOre extends Solid
{
	protected $id = self::COPPER_ORE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 3;
	}

	public function getName() : string
	{
		return "Copper Ore";
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get(ItemIds::RAW_COPPER)->setCount(FortuneDropHelper::weighted($item, min: 2, maxBase: 5))
		];
	}

	public function isAffectedBySilkTouch() : bool
	{
		return true;
	}
}
