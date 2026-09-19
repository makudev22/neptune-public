<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;

class NetherGoldOre extends Solid
{
	protected $id = self::NETHER_GOLD_ORE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Nether Gold Ore";
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
		return TieredTool::TIER_WOODEN;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [ItemFactory::get(ItemIds::GOLD_NUGGET)->setCount(FortuneDropHelper::weighted($item, min: 2, maxBase: 6))];
	}

	public function isAffectedBySilkTouch() : bool
	{
		return true;
	}
}
