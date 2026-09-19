<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

use pocketmine\inventory\random\WeightedRandomItem;

class EnchantmentData extends WeightedRandomItem {

	public function __construct(
		public Enchantment $enchantment,
		public int $enchantmentLevel
	){
		parent::__construct($this->enchantment->getRarity());
	}

	public function toEnchantmentInstance() : EnchantmentInstance {
		return new EnchantmentInstance($this->enchantment, $this->enchantmentLevel);
	}
}
