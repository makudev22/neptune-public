<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;

use function mt_rand;

class LapisOre extends Solid
{
	protected $id = self::LAPIS_ORE;

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
		return TieredTool::TIER_STONE;
	}

	public function getName() : string
	{
		return "Lapis Lazuli Ore";
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [ItemFactory::get(ItemIds::DYE, 4)->setCount(FortuneDropHelper::weighted($item, 4, 8))];
	}

	protected function getXpDropAmount() : int
	{
		return mt_rand(2, 5);
	}

	public function isAffectedBySilkTouch() : bool
	{
		return true;
	}
}
