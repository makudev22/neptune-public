<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class Gravel extends Fallable
{
	protected $id = self::GRAVEL;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Gravel";
	}

	public function getHardness() : float
	{
		return 0.6;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_SHOVEL;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		if (FortuneDropHelper::bonusChanceDivisor($item, 10, 3)) {
			return [
				ItemFactory::get(Item::FLINT)
			];
		}

		return parent::getDropsForCompatibleTool($item);
	}

	public function isAffectedBySilkTouch() : bool
	{
		return true;
	}
}
