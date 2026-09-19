<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;

use function mt_rand;

class EmeraldOre extends Solid
{
	protected $id = self::EMERALD_ORE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Emerald Ore";
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_IRON;
	}

	public function getHardness() : float
	{
		return 3;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [ItemFactory::get(ItemIds::EMERALD)->setCount(FortuneDropHelper::weighted($item, 1, 1))];
	}

	protected function getXpDropAmount() : int
	{
		return mt_rand(3, 7);
	}

	public function isAffectedBySilkTouch() : bool
	{
		return true;
	}
}
