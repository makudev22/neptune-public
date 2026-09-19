<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

use function mt_rand;

class GildedBlackstone extends Solid
{
	protected $id = self::GILDED_BLACKSTONE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getHardness() : float
	{
		return 1.5;
	}

	public function getBlastResistance() : float
	{
		return 6;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		if (FortuneDropHelper::bonusChanceDivisor($item, 10, 3)) {
			return [ItemFactory::get(ItemIds::GOLD_NUGGET)->setCount(mt_rand(2, 5))];
		}

		return parent::getDropsForCompatibleTool($item);
	}

	public function isAffectedBySilkTouch() : bool
	{
		return true;
	}
}
