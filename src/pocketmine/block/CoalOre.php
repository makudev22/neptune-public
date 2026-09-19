<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;

use function mt_rand;

class CoalOre extends Solid
{
	protected $id = self::COAL_ORE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
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

	public function getName() : string
	{
		return "Coal Ore";
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [ItemFactory::get(ItemIds::COAL)->setCount(FortuneDropHelper::weighted($item, 1, 1))];
	}

	protected function getXpDropAmount() : int
	{
		return mt_rand(0, 2);
	}

	public function isAffectedBySilkTouch() : bool
	{
		return true;
	}
}
