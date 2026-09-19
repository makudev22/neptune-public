<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

use pocketmine\inventory\random\WeightedRandom;
use pocketmine\item\Book;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\utils\Random;

use function count;

class EnchantmentHelper {

	/**
	 * @return EnchantmentData[]|null
	 */
	public static function buildEnchantmentList(Random $random, Item $item, int $level) : ?array {
		$enchantAbility = $item->getEnchantAbility();
		if ($enchantAbility < -0) {
			return null;
		}

		$enchantAbility = (int) ($enchantAbility / 2);
		$enchantAbility = 1 + $random->nextBoundedInt(($enchantAbility >> 1) + 1) + $random->nextBoundedInt(($enchantAbility >> 1) + 1);
		$randomLevel = $enchantAbility + $level;
		$chance = ($random->nextFloat() + $random->nextFloat() - 1.0) * 0.15;
		$newLevel = (int) ((float) $randomLevel * (1.0 + $chance) + 0.5);
		if ($newLevel < 1) {
			$newLevel = 1;
		}

		$list = null;
		$enchantments = EnchantmentHelper::mapEnchantmentData($newLevel, $item);

		if (count($enchantments) !== 0) {
			/** @var EnchantmentData $enchantmentData */
			$enchantmentData = WeightedRandom::getRandomItem($enchantments, null, $random);
			if ($enchantmentData !== null) {
				$list = [];
				$list[] = $enchantmentData;

				for ($i = $newLevel; $random->nextBoundedInt(50) <= $i; $i >>= 1) {
					foreach ($enchantments as $id => $_) {
						$dont = true;
						foreach ($list as $enchantmentData1) {
							if (!$enchantmentData1->enchantment->canApplyTogether(Enchantment::getEnchantment($id))) {
								$dont = false;
								break;
							}
						}

						if (!$dont) {
							unset($enchantments[$id]);
						}
					}

					if (count($enchantments) !== 0) {
						/** @var EnchantmentData $enchantmentData */
						$enchantmentData2 = WeightedRandom::getRandomItem($enchantments, null, $random);
						$list[] = $enchantmentData2;
					}
				}
			}
		}

		return $list;
	}

	/**
	 * @return EnchantmentData[]
	 */
	public static function mapEnchantmentData(int $level, Item $item) : array{
		$map = [];
		$isBook = $item instanceof Book;

		foreach (Enchantment::getAllEnchantments() as $enchantment) {
			if ($enchantment !== null) {
				if ($enchantment->canApply($item) || $isBook) {
					for ($i = 1; $i <= $enchantment->getMaxLevel(); ++$i) {
						if ($level >= $enchantment->getMinEnchantAbility($i) && $level <= $enchantment->getMaxEnchantability($i)) {
							$map[$enchantment->getId()] = new EnchantmentData($enchantment, $i);
						}
					}
				}
			}
		}

		return $map;
	}

	public static function addRandomEnchantment(Random $random, Item $item, int $level) : Item {
		$list = self::buildEnchantmentList($random, $item, $level);
		if ($item instanceof Book) {
			$item = ItemFactory::get(ItemIds::ENCHANTED_BOOK);
		}

		if ($list !== null) {
			foreach ($list as $enchantmentData) {
				$item->addEnchantment($enchantmentData->toEnchantmentInstance());
			}
		}

		return $item;
	}
}
