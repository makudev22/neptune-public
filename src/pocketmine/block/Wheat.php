<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

use pocketmine\item\ItemIds;

class Wheat extends Crops
{
	protected $id = self::WHEAT_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Wheat Block";
	}

	public function getSeed() : Item {
		return ItemFactory::get(ItemIds::WHEAT_SEEDS);
	}

	public function getCrop() : Item {
		return ItemFactory::get(ItemIds::WHEAT);
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		if ($this->isMaxAge()) {
			return [
				ItemFactory::get(ItemIds::WHEAT),
				ItemFactory::get(ItemIds::WHEAT_SEEDS, 0, FortuneDropHelper::binomial($item, 0))
			];
		} else {
			return [
				ItemFactory::get(ItemIds::WHEAT_SEEDS)
			];
		}
	}
}
