<?php


declare(strict_types=1);

namespace pocketmine\inventory\random;

use pocketmine\utils\Random;

class WeightedRandom {

	/**
	 * @param WeightedRandomItem[] $items
	 */
	public static function getTotalWeight(array $items) : int {
		$weights = 0;
		foreach ($items as $item) {
			$weights += $item->itemWeight;
		}

		return $weights;
	}

	/**
	 * @param WeightedRandomItem[] $items
	 */
	public static function getRandomItem(array $items, ?int $weight, ?Random $random = null) : ?WeightedRandomItem {
		if ($weight === null) {
			$weight = WeightedRandom::getTotalWeight($items);
		}

		if ($random !== null) {
			if ($weight <= 0) {
				throw new \InvalidArgumentException("Weight must be greater than 0");
			}

			$weight = $random->nextBoundedInt($weight);
		}

		foreach ($items as $item) {
			$weight -= $item->itemWeight;
			if ($weight < 0) {
				return $item;
			}
		}

		return null;
	}
}
