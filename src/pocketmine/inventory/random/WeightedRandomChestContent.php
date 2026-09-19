<?php


declare(strict_types=1);

namespace pocketmine\inventory\random;

use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\utils\Random;

class WeightedRandomChestContent extends WeightedRandomItem {

	/**
	 * @param WeightedRandomChestContent[] $list
	 */
	public static function generateChestContents(Random $random, array $list, Inventory $inventory, int $max) : void{
		for ($i = 0; $i < $max; ++$i) {
			/** @var WeightedRandomChestContent $weightedRandomChestContent */
			$weightedRandomChestContent = WeightedRandom::getRandomItem($list, null, $random);
			$count = $weightedRandomChestContent->minStackSize + $random->nextBoundedInt($weightedRandomChestContent->maxStackSize - $weightedRandomChestContent->minStackSize + 1);

			if ($weightedRandomChestContent->item->getMaxStackSize() >= $count) {
				$item = clone $weightedRandomChestContent->item;
				$item->setCount($count);
				$inventory->setItem($random->nextBoundedInt($inventory->getSize()), $item);
			} else {
				for ($k = 0; $k < $count; ++$k) {
					$item = clone $weightedRandomChestContent->item;
					$item->setCount(1);
					$inventory->setItem($random->nextBoundedInt($inventory->getSize()), $item);
				}
			}
		}
	}

	public function __construct(
		public Item $item,
		public int $minStackSize,
		public int $maxStackSize,
		int $itemWeight
	){
		parent::__construct($itemWeight);
	}
}
