<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

use pocketmine\item\ItemIds;

class Potato extends Crops {
	protected $id = self::POTATO_BLOCK;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Potato Block";
	}

	public function getSeed() : Item {
		return ItemFactory::get(ItemIds::POTATO);
	}

	public function getCrop() : Item {
		return ItemFactory::get(ItemIds::POTATO);
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		$result = [
			//min/max would be 2-5 in Java
			ItemFactory::get(ItemIds::POTATO, 0, $this->isMaxAge() ? FortuneDropHelper::binomial($item, 1) : 1)
		];

		if ($this->isMaxAge() && $this->level->random->nextBoundedInt(50) === 0) {
			$result[] = ItemFactory::get(ItemIds::POISONOUS_POTATO);
		}

		return $result;
	}
}
